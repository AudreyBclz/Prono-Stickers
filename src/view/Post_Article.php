<?php
session_start();
require '../../src/controller/function.php';
require_once 'elements/head.php';
require_once 'elements/footer.php';
head();
include'elements/nav.php';


$bdd=dbconnect();
    if (isset($_POST["title"]) && isset($_POST["content"]) && isset($_FILES["picture"]))
    {
        if ($_POST["title"] === "" || $_POST["content"] === "")
        {
            echo '<span class="alert-warning"> Veuillez remplir tous les champs.</span><br/>';
        } elseif (!($_FILES["picture"]['type'] == 'image/jpeg' || $_FILES["picture"]['type'] == 'image/png'))
        {
            echo '<span class="alert-warning">Seul les formats JPEG et PNG sont acceptés, trouvez une autre image</span>';
        } else
        {
            if ($_FILES["picture"]['type'] == 'image/jpeg')
            {
                $suffix=".jpg";
            } else
            {
                $suffix=".png";
            }
            move_uploaded_file($_FILES['picture']['tmp_name'], '../../assets/img/articles/' . $_FILES['picture']['name']);

            $pseudo = $_SESSION['pseudo'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $request = $bdd->prepare('INSERT INTO article (pseudo,title,content,suffix) VALUES(:pseudo,:title,:content,:suffix)');
            $request->execute(array(
                'pseudo' => $pseudo,
                'title' => $title,
                'content' => $content,
                'suffix'=>$suffix
            ));
            $request->closeCursor();
            $indice=intval($bdd->lastInsertId());
            rename('../../assets/img/articles/' . $_FILES['picture']['name'], '../../assets/img/articles/articles'.$indice.$suffix);
            echo '<span class="alert-info">Article bien enregistré.</span>';
        }
    }
?>

    <h1>Poster un article</h1>
    <main id="main">
        <div class="col-lg-6">
            <form method="post" action="Post_Article.php" enctype="multipart/form-data">
                <fieldset class="bg-dark">
                    <div class="form-group justify-content-center">
                        <label for="titre" class="col-form-label-sm col-sm-12">Quel est le titre de votre article? </label>
                        <input type="text" id="titre" class="form-control-sm" name="title"/><br/>
                        <label for="contenu" class="col-form-label-sm col-sm-12">Entrer son contenu:</label>
                        <textarea id="contenu" name="content" rows="5"></textarea>
                        <label for="image" class="col-form-label-sm col-sm-12">Image (JPG,PNG)</label>
                        <input type="file" class="form-control-file" name="picture" id="image"/><br/>
                        <input type="submit" value="Envoyer" name="envoi" class="btn-danger"/>
                    </div>
                </fieldset>
            </form>
        </div>
        <div id="chat" class="d_none col-lg-5 bg-light-gray">
            <form id="formChat" method="post" action="Post_Article.php">
                <input type="text" placeholder="Ecrivez votre message ici" class="form-control-sm bg-light my-2 mb-2" id="msg"/>
                <input type="submit" value="Envoyer" name="envoi" class="btn-danger btn-sm "/>
            </form>
            <div id="zoneChat">
                <p class="bg-dark msgChat"> @Audrey le 16/02/20 à 10h30<br/>Bla BLZA VKSEDJFSOJFOSHJOHSF JPEJ PJEF J EPI J£J ZO£J F£J£ FJ </p>
                <p class="bg-dark msgChat"> @juzja le 16/02/20 à 10h10<br/> kjzzd hg HZUYdih iizi$ dzhdzeig ii uizh odzo jdzohd </p>
                <p class="bg-dark msgChat">@JAYGL le 16/02/20 à 10h03<br/> lzidzhgi ohzd ihdjzhd hdzighdg hi zdh iz hpih zphd hpozh dp</p>
            </div>
        </div>
    </main>

 <?php
footer();
?>
