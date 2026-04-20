<?php
session_start();
include "ojan.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit;
}

$id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id='$id'")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - NYMATE</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        /* Tambahan style khusus Profile */
        .profile-header {
            background-color: #A8D8EA;
            height: 200px;
            border-radius: 0 0 40px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            padding-bottom: 20px;
            position: relative;
        }

        .back-btn {
            position: absolute;
            top: 40px;
            left: 20px;
            font-size: 24px;
            color: white;
            text-decoration: none;
        }

        .profile-pic-container {
            width: 110px;
            height: 110px;
            background: white;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: absolute;
            bottom: -55px;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            background: #ccc; /* Ganti dengan gambar nanti */
            border-radius: 50%;
            overflow: hidden;
        }

        .profile-info {
            margin-top: 65px;
            text-align: center;
            padding: 0 20px;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .profile-username {
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .profile-bio {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .edit-profile-btn {
            background-color: #FFD54F;
            border: none;
            padding: 10px 30px;
            border-radius: 20px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 213, 79, 0.3);
        }

        /* Stats Grid */
        .stats-row {
            display: flex;
            justify-content: space-around;
            margin: 25px 20px;
            background: rgba(255,255,255,0.5);
            padding: 15px;
            border-radius: 20px;
        }

        .stat-item { text-align: center; }
        .stat-val { font-weight: 600; font-size: 18px; display: block; }
        .stat-label { font-size: 12px; color: #888; }

        /* Form Edit (Hidden by Default) */
        #edit-section {
            display: none;
            padding: 20px;
        }

        .form-edit-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-edit-group label {
            display: block;
            margin-left: 10px;
            margin-bottom: 5px;
            font-size: 13px;
            color: #666;
            font-weight: 600;
        }

        .input-edit {
            width: 100%;
            padding: 12px 15px;
            border-radius: 15px;
            border: none;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            outline: none;
            font-family: inherit;
        }

        .save-btn {
            background-color: #81D4FA;
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="mobile-container">
        
        <!-- VIEW PROFILE SECTION -->
        <div id="view-section">
            <header class="profile-header">
                <a href="index.php" class="back-btn">←</a>
                <div class="profile-pic-container">
                    <div class="profile-pic">
                        <!-- Placeholder gambar -->
                        <img src="<?= !empty($user['avatar']) ? $user['avatar'] : 'https://via.placeholder.com/150' ?>" 
     alt="Avatar" 
     style="width:100%">
                    </div>
                </div>
            </header>

           <div class="profile-info">
    <h2><?= $user['nama'] ?></h2>
    <p>@<?= $user['username'] ?></p>
    <p><?= $user['bio'] ?></p>

    <button class="edit-profile-btn" onclick="toggleEdit()">Edit Profile</button>
</div>

            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-val">12</span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val">450</span>
                    <span class="stat-label">Gatherings</span>
                </div>
                <div class="stat-item">
                    <span class="stat-val">89</span>
                    <span class="stat-label">Rewards</span>
                </div>
            </div>

            <div class="feed-tab">MY ACTIVITY</div>
            <div class="feed-content" style="margin-bottom: 100px;">
                <p style="text-align: center; color: #888; padding: 20px;">No recent activities yet.</p>
            </div>
        </div>

        <!-- EDIT PROFILE SECTION -->
        <div id="edit-section">
            <div style="display:flex; align-items:center; margin-bottom:30px;">
                <button onclick="toggleEdit()" style="background:none; border:none; font-size:20px; cursor:pointer;">✕</button>
                <h2 style="margin-left:20px; font-size:20px;">Edit Profile</h2>
            </div>

            <div style="text-align:center; margin-bottom:30px;">
                <div style="width:100px; height:100px; background:#ddd; border-radius:50%; margin:0 auto 10px;"></div>
                <button style="background:none; border:none; color:#81D4FA; font-weight:600; font-size:14px; cursor:pointer;">Change Profile Photo</button>
            </div>

           <form action="update_profile.php" method="POST" enctype="multipart/form-data">

    <div class="form-edit-group">
        <button type="submit" class="save-btn">Save Changes</button>
        <label>Foto Profil</label>
        <input type="file" name="avatar" class="input-edit">
    </div>

    <div class="form-edit-group">
        <label>Full Name</label>
        <input type="text" name="nama" class="input-edit" value="<?= $user['nama'] ?>">
    </div>

    <div class="form-edit-group">
        <label>Username</label>
        <input type="text" name="username" class="input-edit" value="<?= $user['username'] ?>">
    </div>

    <div class="form-edit-group">
        <label>Bio</label>
        <textarea name="bio" class="input-edit" rows="3"><?= $user['bio'] ?></textarea>
    </div>

    <div class="form-edit-group">
        <label>Email</label>
        <input type="email" name="email" class="input-edit" value="<?= $user['email'] ?>">
    </div>
    
</form>
        </div>

        <!-- Navigasi Bawah tetap ada -->
        <nav class="bottom-nav">
    <a href="index.html" class="nav-item active">
        <span class="material-symbols-outlined">home</span>
    </a>
    <a href="peta.html" class="nav-item">
        <span class="material-symbols-outlined">location_on</span>
    </a>
    <a href="kamera.html" class="nav-item camera">
        <span class="material-symbols-outlined">photo_camera</span>
    </a>
    <a href="chat.html" class="nav-item">
        <span class="material-symbols-outlined">chat</span>
    </a>
    <a href="profile.html" class="nav-item">
        <span class="material-symbols-outlined">person</span>
    </a>
</nav>
    </div>

    <script>
        function toggleEdit() {
            var view = document.getElementById('view-section');
            var edit = document.getElementById('edit-section');
            
            if (view.style.display === 'none') {
                view.style.display = 'block';
                edit.style.display = 'none';
            } else {
                view.style.display = 'none';
                edit.style.display = 'block';
            }
        }
    </script>
</body>
</html>