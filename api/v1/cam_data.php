<?php
require '../APIdbconfig.php';

// Préparez la réponse JSON
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = '../../uploads/';
    
    // Vérifiez si une image a été téléchargée sans erreur
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        if ($image['error'] == UPLOAD_ERR_OK) {
            $imageName = $image['name'];
            $imageTmpName = $image['tmp_name'];
            $imageSize = $image['size'];
            $imageType = $image['type'];
            $uploadFile = $uploadDir . basename($imageName);
            
            if (move_uploaded_file($imageTmpName, $uploadFile)) {
                $response['image_status'] = 'success';
                $response['image_message'] = 'Image téléchargée avec succès';
                $response['image_url'] = $uploadFile;
                $photoPath = 'uploads/' . basename($imageName);
            } else {
                $response['image_status'] = 'error';
                $response['image_message'] = 'Erreur lors du déplacement de l\'image';
            }
        } else {
            $response['image_status'] = 'error';
            $response['image_message'] = 'Erreur lors du téléchargement de l\'image: ' . $image['error'];
        }
    } else {
        $photoPath = ''; // Aucun fichier image téléchargé
    }

    // Vérifiez si une vidéo a été téléchargée sans erreur
    if (isset($_FILES['video'])) {
        $video = $_FILES['video'];
        if ($video['error'] == UPLOAD_ERR_OK) {
            $videoName = $video['name'];
            $videoTmpName = $video['tmp_name'];
            $videoSize = $video['size'];
            $videoType = $video['type'];
            $uploadFile = $uploadDir . basename($videoName);
            
            if (move_uploaded_file($videoTmpName, $uploadFile)) {
                $response['video_status'] = 'success';
                $response['video_message'] = 'Vidéo téléchargée avec succès';
                $response['video_url'] = $uploadFile;
                $videoPath = 'uploads/' . basename($videoName);
            } else {
                $response['video_status'] = 'error';
                $response['video_message'] = 'Erreur lors du déplacement de la vidéo';
            }
        } else {
            $response['video_status'] = 'error';
            $response['video_message'] = 'Erreur lors du téléchargement de la vidéo: ' . $video['error'];
        }
    } else {
        $videoPath = ''; // Aucun fichier vidéo téléchargé
    }

    // Insérer les données dans la base de données
    if ($photoPath || $videoPath) {
        $dateHeure = date('Y-m-d H:i:s');
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $query = "INSERT INTO camera (Video, Photo, Date_heure) VALUES (:video, :photo, :date_heure)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':video', $videoPath);
            $stmt->bindParam(':photo', $photoPath);
            $stmt->bindParam(':date_heure', $dateHeure);
            
            if ($stmt->execute()) {
                $response['db_status'] = 'success';
                $response['db_message'] = 'Données insérées dans la base de données';
            } else {
                $response['db_status'] = 'error';
                $response['db_message'] = 'Erreur lors de l\'insertion des données dans la base de données';
            }
        } catch (PDOException $e) {
            $response['db_status'] = 'error';
            $response['db_message'] = 'Erreur de connexion à la base de données: ' . $e->getMessage();
        }
    } else {
        $response['db_status'] = 'error';
        $response['db_message'] = 'Aucun fichier à insérer dans la base de données';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Méthode non autorisée';
}

// Définissez le type de contenu comme JSON
header('Content-Type: application/json');

// Encodez le tableau de réponse en JSON et renvoyez-le
echo json_encode($response);
?>
