<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Profilim</title>

    <!-- Custom fonts for this template-->
    <link href="/../../assets/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">

</head>

<? $dir = __DIR__;?>

<!-- Navbar Begin  -->
<nav class="navbar navbar-expand-lg sticky-top navbar-light" style="background-color: #e3f2fd;">
  <div class="container-fluid">
    <a class="navbar-brand" href="/main/index">Haber Sitesi</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 170px;" id="categories">
      <!-- Kategoriler -->
      </ul>
      <form class="d-flex" id="buttons">
      <!-- Buttonlar -->
    </div>
  </div>
</nav>




<!-- Navbar End  -->

<div class="container">
    <div class="d-flex justify-content-center">
    <div class="mt-3">
            <h2>
                Profil Bilgilerimi Güncelle
            </h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-9 mt-5 border border-4 rounded">
        <form class="abc">
        <div class="row mt-3 justify-content-center" id="errMessage">
        </div>   
        <div class="row mt-3 justify-content-center" id="panel">
        </div>
        <div class="row mt-3 justify-content-center">
                <div class="col-3 mt-1">
                    <label for="ad">Ad :</label>
                </div>
                <div class="col-6">
                    <input type="text" name="ad" id="ad" class="form-control" placeholder="Adınız"/>
                </div>
            </div>
            <div class="row mt-3 justify-content-center">
                <div class="col-3 mt-1">
                    <label for="soyad">Soyad :</label>
                </div>
                <div class="col-6">
                    <input type="text" name="soyad" id="soyad" class="form-control" placeholder="Soyadınız"/>
                </div>
            </div>
            <div class="row mt-3 justify-content-center">
                <div class="col-3 mt-1">
                    <label for="email_sign">E-mail :</label>
                </div>
                <div class="col-6">
                    <input type="email" name="email" id="email" class="form-control" placeholder="E-mail Adresiniz"/>
                </div>
            </div>
            <div class="row mt-3 justify-content-center">
                <div class="col-3 mt-1">
                    <label for="password_sign">Şifre :</label>
                </div>
                <div class="col-6">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Şifreniz"/>
                </div>
            </div>
            <div class="row mt-3 justify-content-center">
                <div class="col-3 mt-1">
                    <label for="password_sign2">Şifre Tekrar :</label>
                </div>
                <div class="col-6">
                    <input type="password" name="password2" id="password2" class="form-control" placeholder="Şifreniz Tekrar"/>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mt-3 mb-3">
                    <span onclick=validation() class="btn btn-primary mt-3" style="float:right;">Kaydet</span>
                </div>
            </div>
        </form>
            <div class="row text-center">
                <p>Not 1 : Şifrenizi güncellemek istemiyorsanız şifre alanlarını boş bırakınız.</p>
            </div>
            <div class="row mt-3 justify-content-center mb-3">
                <div id="error">
                        <!-- MEsaj buraya -->
                </div>
                <div class="col-3 mt-1">
                    <label class="form-check-label mt-2" for="flexCheckDefault">
                            Hesabımı Silmek İstiyorum :
                        </label>
                    </div>
                <div class="col-6">    
                <span onclick=deleteaccount() class="btn btn-danger mt-3" style="float:left;">Sil</span>
                </div>   
            </div>
        </div>
    </div>
</div>



<script src="/../../assets/bootstrap/js/bootstrap.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="/../../assets/js/authentication.js"></script>
<script type="text/javascript" src="/../../assets/js/navbar.js"></script>
<script type="text/javascript" src="/../../assets/js/categories.js"></script>
<script type="text/javascript" src="/../../assets/js/user.js"></script>
<script type="text/javascript" src="/../../assets/js/deleteaccount.js"></script>

</body>
</html>