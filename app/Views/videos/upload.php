<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Database connection
include '../../db.php';

$videosDirectory = '../../../downloads';
$errors = [];
$videosPerPage = 10; // Number of videos per page

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_video'])) {
    $customerName = strtolower(str_replace(' ', '_', $_POST['customer_name']));
    $startTime = strtolower(str_replace(' ', '_', $_POST['start_time']));
    $endTime = strtolower(str_replace(' ', '_', $_POST['end_time']));

    if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['video_file']['tmp_name'];
        $fileName = $_FILES['video_file']['name'];
        $fileSize = $_FILES['video_file']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExtension !== 'mp4') {
            $errors[] = 'Only MP4 files are allowed.';
        } elseif ($fileSize > 30 * 1024 * 1024) {
            $errors[] = 'File size must be less than 30MB.';
        } else {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . "_{$customerName}_start{$startTime}_end{$endTime}.{$fileExtension}";
            $destination = "$videosDirectory/$newFileName";

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $videoLink = $destination;

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("INSERT INTO tbl_videos (video_name, video_link, customer_name, start_time, end_time, deleted) VALUES (?, ?, ?, ?, ?, 0)");
                $stmt->bind_param('sssss', $newFileName, $videoLink, $customerName, $startTime, $endTime);

                if (!$stmt->execute()) {
                    $errors[] = 'Database insertion failed.';
                }

                $stmt->close();
                $conn->close();
            } else {
                $errors[] = 'Failed to upload file.';
            }
        }
    } else {
        $errors[] = 'No file uploaded or upload error occurred.';
    }
}

// Handle toggle delete and file removal
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_delete'])) {
        $videoId = $_POST['video_id'];
        $conn->query("UPDATE tbl_videos SET deleted = NOT deleted WHERE id = $videoId");
    }

    if (isset($_POST['remove_file'])) {
        $videoId = $_POST['video_id'];
        $videoResult = $conn->query("SELECT video_link FROM tbl_videos WHERE id = $videoId AND deleted = 1");
        $conn->query("UPDATE tbl_videos SET file_removed = 1 WHERE id = $videoId");
        if ($video = $videoResult->fetch_assoc()) {
            if (unlink($video['video_link'])) {
                // $conn->query("DELETE FROM tbl_videos WHERE id = $videoId");
            }
        }
    }
}

// Pagination setup
$totalVideosResult = $conn->query("SELECT COUNT(*) AS total FROM tbl_videos");
$totalVideos = $totalVideosResult->fetch_assoc()['total'] ?? 0;
$totalPages = max(ceil($totalVideos / $videosPerPage), 1);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;
$offset = ($currentPage - 1) * $videosPerPage;

// Fetch videos with pagination
$videosResult = $conn->query("SELECT * FROM tbl_videos ORDER BY id DESC LIMIT $offset, $videosPerPage");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Screen Video Ads</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>


<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title text-primary mb-4">🎬 Manage Screen Video Ads</h2>

                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-4">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="start_time" class="form-label">Start Time</label>
                        <select id="start_time" name="start_time" class="form-select" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>am"><?= $i ?> AM</option>
                                <option value="<?= $i ?>pm"><?= $i ?> PM</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="end_time" class="form-label">End Time</label>
                        <select id="end_time" name="end_time" class="form-select" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>am"><?= $i ?> AM</option>
                                <option value="<?= $i ?>pm"><?= $i ?> PM</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="video_file" class="form-label">Upload Video File</label>
                        <input type="file" id="video_file" name="video_file" class="form-control" required>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" name="upload_video" class="btn btn-primary px-4">
                            <i class="bi bi-upload"></i> Upload Video
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title mb-4">📂 Uploaded Videos</h3>
                <?php if ($totalVideos === 0): ?>
                    <div class="alert alert-warning">No videos uploaded yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Video Name</th>
                                    <th>Customer</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                    <th>File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($video = $videosResult->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= $video['video_link'] ?>" class="video-link text-decoration-none" data-video-link="<?= $video['video_link'] ?>">
                                                🎥 <?= htmlspecialchars($video['video_name'] ?? 'Unknown') ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($video['customer_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($video['start_time'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($video['end_time'] ?? '-') ?></td>
                                        <td>
                                            <?= $video['deleted'] == 1
                                                ? '<span class="badge bg-danger">Deleted</span>'
                                                : '<span class="badge bg-success">Active</span>' ?>
                                        </td>
                                        <td>
                                            <?= $video['file_removed'] == 1
                                                ? '<span class="badge bg-danger">Removed</span>'
                                                : '<span class="badge bg-success">Available</span>' ?>
                                        </td>
                                        <td>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                                                <button type="submit" name="toggle_delete" class="btn btn-sm btn-warning">
                                                    <?= $video['deleted'] ? 'Restore' : 'Delete' ?>
                                                </button>
                                            </form>
                                            <?php if ($video['deleted']): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                                                    <button type="submit" name="remove_file" class="btn btn-sm btn-danger">
                                                        Remove File
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Video Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <video id="modalVideo" controls class="w-100 rounded shadow-sm">
                            <source src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Script -->
        <script>
            $(function () {
                $('.video-link').on('click', function (e) {
                    e.preventDefault();
                    const videoSrc = $(this).data('video-link');
                    $('#modalVideo').attr('src', videoSrc);
                    $('#videoModal').modal('show');
                });

                $('#videoModal').on('hidden.bs.modal', function () {
                    $('#modalVideo').attr('src', '');
                });
            });
        </script>
    </div>
</body>



</html>
