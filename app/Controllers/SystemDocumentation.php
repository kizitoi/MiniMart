<?php

namespace App\Controllers;
use App\Libraries\UserDataHelper;
use CodeIgniter\Controller;
use App\Models\SystemDocumentationModel;

class SystemDocumentation extends BaseController
{
    protected $model;
    protected $helper;

    public function __construct()
    {
        $this->model = new SystemDocumentationModel();
        $this->helper = new UserDataHelper();
    }

    // Admin - Listing with search and pagination
    public function index()
    {
        $sessionData = $this->helper->load();
        $search = $this->request->getGet('search');

        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        $query = $this->model;
        if ($search) {
            $query = $query->like('title', $search);
        }

 

        $data = [
            'docs' => $query->paginate($perPage, 'docs', $currentPage),
            'pager' => $this->model->pager,
            'search' => $search,
        ] + $sessionData;

        return view('admin/system_documentation/index', $data);
    }

    public function add()
    {
        $data = $this->helper->load();
        return view('admin/system_documentation/add', $data);
    }

    public function store()
    {
        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH.'uploads/docs', $newName);

            $this->model->save([
                'title' => $this->request->getPost('title'),
                'file_name' => $newName
            ]);

            return redirect()->to('admin/system_documentation')->with('success', 'Document uploaded successfully.');
        }

        return redirect()->back()->with('error', 'File upload failed.');
    }

    public function edit($id)
    {
        $doc = $this->model->find($id);
        $data = $this->helper->load() + ['doc' => $doc];
        return view('admin/system_documentation/edit', $data);
    }

    public function update($id)
    {
        $doc = $this->model->find($id);
        $file = $this->request->getFile('file');

        $updateData = ['title' => $this->request->getPost('title')];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old file
            if (file_exists(WRITEPATH.'uploads/docs/'.$doc['file_name'])) {
                unlink(WRITEPATH.'uploads/docs/'.$doc['file_name']);
            }

            $newName = $file->getRandomName();
            $file->move(WRITEPATH.'uploads/docs', $newName);
            $updateData['file_name'] = $newName;
        }

        $this->model->update($id, $updateData);
        return redirect()->to('admin/system_documentation')->with('success', 'Document updated.');
    }

    public function delete($id)
    {
        $doc = $this->model->find($id);
        if ($doc && file_exists(WRITEPATH.'uploads/docs/'.$doc['file_name'])) {
            unlink(WRITEPATH.'uploads/docs/'.$doc['file_name']);
        }

        $this->model->delete($id);
        return redirect()->to('admin/system_documentation')->with('success', 'Document deleted.');
    }

    // User view/download only
    public function viewList()
    {
        $sessionData = $this->helper->load();
        $search = $this->request->getGet('search');
        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        $query = $this->model;
        if ($search) {
            $query = $query->like('title', $search);
        }

        $data = [
            'docs' => $query->paginate($perPage, 'group1', $currentPage),
            'pager' => $this->model->pager,
            'search' => $search,
        ] + $sessionData;

        return view('system_documentation/view', $data);
    }

    public function download($id)
    {
        $doc = $this->model->find($id);
        if ($doc) {
            return $this->response->download(WRITEPATH.'uploads/docs/'.$doc['file_name'], null);
        }
        return redirect()->back()->with('error', 'File not found.');
    }
}
