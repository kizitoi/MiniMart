<?php

namespace App\Controllers;

use App\Libraries\UserDataHelper;
use App\Models\RemindersModel;
use CodeIgniter\I18n\Time;

class Reminders extends BaseController
{
    protected $model;
    protected $helper;

    public function __construct()
    {
        $this->model  = new RemindersModel();
        $this->helper = new UserDataHelper();
        date_default_timezone_set('Africa/Nairobi'); // ensure local handling
    }

    // List + search + pagination
    public function index()
    {
        $sessionData = $this->helper->load();

        // Derive logged-in user ID from session
        $userId = session('user_id'); // adjust if your session key differs
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Login required.');
        }

        $search    = $this->request->getGet('search');
        $status    = $this->request->getGet('status'); // optional filter by status
        $perPage   = 10;

        $query = $this->model->searchWithUser($search, (int)$userId);
        if ($status && in_array($status, ['New','Pending','Completed'])) {
            $query = $query->where('status', $status);
        }

        $data = $sessionData + [
            'title'     => 'Notes & Reminders',
            'search'    => $search,
            'status'    => $status,
            'reminders' => $query->paginate($perPage, 'reminders'),
            'pager'     => $this->model->pager,
        ];

        return view('reminders/index', $data);
    }

    public function add()
    {
        $data = $this->helper->load() + ['title' => 'Add Reminder'];
        return view('reminders/add', $data);
    }

    public function store()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Login required.');
        }

        $title    = trim($this->request->getPost('title'));
        $about    = trim($this->request->getPost('about'));
        $date     = $this->request->getPost('remind_date'); // yyyy-mm-dd
        $time     = $this->request->getPost('remind_time'); // HH:MM
        $status   = $this->request->getPost('status');

        if (!$title || !$date || !$time) {
            return redirect()->back()->withInput()->with('error', 'Title, date, and time are required.');
        }

        // Combine into "Africa/Nairobi" local datetime
        $remindAt = Time::createFromFormat('Y-m-d H:i', "{$date} {$time}", 'Africa/Nairobi');

        $this->model->save([
            'user_id'   => (int)$userId,
            'title'     => $title,
            'about'     => $about,
            'remind_at' => $remindAt->toDateTimeString(),
            'status'    => in_array($status, ['New','Pending','Completed']) ? $status : 'New',
        ]);

        return redirect()->to('reminders')->with('success', 'Reminder created.');
    }

    public function edit($id)
    {
        $userId   = session('user_id');
        $reminder = $this->model->where('user_id', (int)$userId)->find($id);
        if (!$reminder) {
            return redirect()->to('reminders')->with('error', 'Reminder not found.');
        }

        $data = $this->helper->load() + [
            'title'    => 'Edit Reminder',
            'reminder' => $reminder,
        ];
        return view('reminders/edit', $data);
    }

    public function update($id)
    {
        $userId = session('user_id');
        $exists = $this->model->where('user_id', (int)$userId)->find($id);
        if (!$exists) {
            return redirect()->to('reminders')->with('error', 'Reminder not found.');
        }

        $title  = trim($this->request->getPost('title'));
        $about  = trim($this->request->getPost('about'));
        $date   = $this->request->getPost('remind_date');
        $time   = $this->request->getPost('remind_time');
        $status = $this->request->getPost('status');

        if (!$title || !$date || !$time) {
            return redirect()->back()->withInput()->with('error', 'Title, date, and time are required.');
        }

        $remindAt = \CodeIgniter\I18n\Time::createFromFormat('Y-m-d H:i', "{$date} {$time}", 'Africa/Nairobi');

        $this->model->update($id, [
            'title'     => $title,
            'about'     => $about,
            'remind_at' => $remindAt->toDateTimeString(),
            'status'    => in_array($status, ['New','Pending','Completed']) ? $status : 'New',
        ]);

        return redirect()->to('reminders')->with('success', 'Reminder updated.');
    }

    public function delete($id)
    {
        $userId = session('user_id');
        $exists = $this->model->where('user_id', (int)$userId)->find($id);
        if ($exists) {
            $this->model->delete($id);
            return redirect()->to('reminders')->with('success', 'Reminder deleted.');
        }
        return redirect()->to('reminders')->with('error', 'Reminder not found.');
    }

    // JSON endpoint for today's reminders (for footer popup)
    public function today()
    {
        if (!session('user_id')) {
            return $this->response->setJSON(['ok' => true, 'data' => []]);
        }

        $userId = (int) session('user_id');

        // Start/end of today in Africa/Nairobi
        $start = Time::today('Africa/Nairobi')->setTime(0,0,0);
        $end   = Time::today('Africa/Nairobi')->setTime(23,59,59);

        $rows = $this->model
            ->where('user_id', $userId)
            ->where('remind_at >=', $start->toDateTimeString())
            ->where('remind_at <=', $end->toDateTimeString())
            ->orderBy('remind_at', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'ok'   => true,
            'data' => $rows
        ]);
    }
}
