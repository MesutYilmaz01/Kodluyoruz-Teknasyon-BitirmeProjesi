<?php

namespace Project\Services;

use Project\Helper\Authentication;
use Project\Helper\Authorization;
use Project\Models\User;
use Project\Repositories\UserRepository;
use Project\Helper\Logging;
use Project\Repositories\CategoryRepository;

class UserService{
    public function addToDatabase(){
        if (strlen($_POST["name"]) > 0)
        {
            if (strlen($_POST["surname"]) > 0)
            {
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
                {
                    if ((strlen($_POST["password"]) > 7) && ($_POST["password"] == $_POST["password2"]))
                    {
                        $data = new User();
                        $data->setName($_POST["name"]);
                        $data->setSurname($_POST["surname"]);
                        $data->setEmail($_POST["email"]);
                        $data->setPassword($_POST["password"]);
                        $data->setType($_POST["type"]);
                        $data->setToken(strval(time()));
                        $data->setCreatedAt(date('d-m-Y h:i'));
                        $data->setUpdatedAt(date('d-m-Y h:i'));
                        $repo = new UserRepository();
                        if(Authentication::check())
                        {
                            if (Authorization::isModerator() && ($_POST["type"] == 1 || $_POST["type"] == 2))
                            {
                                Logging::emergency(Authentication::getUser(),"Kullanıcıya  izinsiz yetki verilmeye çalışıldı.");
                                return array(0,"Admin değilseniz admin işlemleri yapmaya kalkmayınız.");
                            }
                        }
                        if (!$repo->selectByEmail($_POST["email"]))
                        {
                            Logging::alert(Authentication::getUser(),"Kayıtlı email ile kayıt güncellenmeye çalışıldı.");
                            return array(0,"Bu email adresi sistemde kayıtlı.");
                        }
                        $result = $repo->create($data);
                        $anonim = new User();
                        $anonim->setName("Bir");
                        $anonim->setSurname("Kullanıcı");
                        $anonim->setType("4");
                        if ($result[0] == 1)
                        {
                            if(!Authentication::check())
                            {
                                Logging::info($anonim,"Yeni bir kullanıcı üye oldu.");
                            }
                            else
                            {
                                Logging::info(Authentication::getUser(),"Veritabanına ".$data->getId()." id'li yeni bir kullanıcı eklendi ");
                            }
                        }
                        else
                        {
                            if(!Authentication::check())
                            {
                                Logging::emergency($anonim,"Üye olurken sorun ile karşılaşıldı.");
                            }
                            else
                            {
                                Logging::emergency(Authentication::getUser(),"Veritabanına kullanıcı eklerken bir hata oluştu ");
                            }
                        }
                        return $result;
                    }
                    return array(0,"Şifreler eşleşmeli ve en az 8 karakter olmalıdır.");
                }
                return array(0,"Email alanı zorunludur");
            } 
            return array(0,"Soyisim alanı zorunludur.");
        }
        return array(0,"İsim alanı zorunludur.");
    }

    public function updateUser(User $user){
        if (strlen($_POST["name"]) > 0)
        {
            if (strlen($_POST["surname"]) > 0)
            {
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
                {
                    if (($_POST["password"] == "" && $_POST["password2"] == "") || 
                    (strlen($_POST["password"]) > 7) && ($_POST["password"] == $_POST["password2"]))
                    {
                        $data = new User();
                        $data->setId($user->getId());
                        $data->setName($_POST["name"]);
                        $data->setSurname($_POST["surname"]);
                        $data->setEmail($_POST["email"]);
                        $password = $_POST["password"] == "" ? $user->getPassword() : $_POST["password"];
                        $data->setPassword($password);
                        $data->setType($_POST['type']);
                        $data->setCreatedAt($user->getCreatedAt());
                        $data->setUpdatedAt(date('d-m-Y h:i'));
                        $repo = new UserRepository();
                        if (Authorization::isModerator() && ($_POST["type"] == 1 || $_POST["type"] == 2))
                        {
                            Logging::emergency(Authentication::getUser(),"Kullanıcıya  izinsiz yetki verilmeye çalışıldı.");
                            return array(0,"Admin değilseniz admin işlemleri yapmaya kalkmayınız.");
                        }
                        if (!$repo->selectByEmail($_POST["email"]) && $_POST["email"] != $user->getEmail())
                        {
                            Logging::alert(Authentication::getUser(),"Kayıtlı email ile kayıt güncellenmeye çalışıldı.");
                            return array(0,"Bu email adresi sistemde kayıtlı.");
                        }
                        $result = $repo->update($data);
                        if ($result[0] == 1)
                        {
                            Logging::info(Authentication::getUser(),"Veritabanında ".$data->getId()." id'li kullanıcı güncellendi ");
                        }
                        else
                        {
                            Logging::emergency(Authentication::getUser(),"Veritabanına ".$data->getId()." id'li kullanıcı güncellenirken bir hata oluştu ");
                        }
                        return $result;
                        }
                    return array(0,"Şifreler eşleşmeli ve en az 8 karakter olmalıdır.");
                }
                return array(0,"Email alanı zorunludur");
            } 
            return array(0,"Soyisim alanı zorunludur.");
        }
        return array(0,"İsim alanı zorunludur.");
    }

    public function getUsers(){
        $repo = new UserRepository();
        //Moderatör için
        if (Authorization::isModerator())
        {
            $data = $repo->getCountForModerator();
            Logging::info(Authentication::getUser(),"Veritabanından Kullanıcılar Sayfası İçin Kullanıcılar Çekildi");
            return $data;
        }
        //Admin için
        $data = $repo->select();
        Logging::info(Authentication::getUser(),"Veritabanından Kullanıcılar Sayfası İçin Kullanıcılar Çekildi");
        return $data;
    }

    public function getUserById(){
        //Burada log kısmı düzenlenmeli.
        $id = $_GET["id"];
        $repo = new UserRepository();
        $data = $repo->selectById($id);
        if (Authentication::check())
        {
            if ($data == false)
            {
                Logging::emergency(Authentication::getUser()," Kullanıcı veritabanından çekilemedi.");
                return false;
            }
            Logging::info(Authentication::getUser(),$data->getId()." id'li kullanıcı veritabanından çekildi.");
        }
        return $data;
    }

    public function getUserByToken(){
        $token = $_GET["token"];
        $repo = new UserRepository();
        $data = $repo->selectByToken($token);
            if ($data == false)
            {
                $temp = new User();
                $temp->setName("Bir");
                $temp->setSurname("Kullanıcı");
                $temp->setType("1");
                Logging::emergency($temp,"Kullanıcı veritabanından çekilemedi.");
                return false;
            }
            Logging::info($data,$data->getId()." id'li kullanıcı veritabanından çekildi.");
            return $data;
    }

    public function deleteUserById(){
        $id = $_GET["id"];
        $repo = new UserRepository();
        $data = $repo->delete($id);
        if ($data == false)
        {
            Logging::emergency(Authentication::getUser(),$id." id'li kullanıcı veritabanından silinemedi.");
            return false;
        }
        Logging::info(Authentication::getUser(),$id." id'li kullanıcı veritabanından silindi.");
        return $data;
    }

    public function getPaginatedUsers($page){
        $limit = 5;
        $repo = new UserRepository();
        $pageStarts = ($page*$limit) - $limit;
        //Moderatör İçin
        if (Authorization::isModerator())
        {
            $data = $repo->getAllForModerator($pageStarts, $limit);
            Logging::info(Authentication::getUser(),"Veritabanından Kullanıcılar Sayfası İçin Kullanıcılar Çekildi");
            return $data;
        }
        //Admin için
            $data = $repo->selectAllWithLimit($pageStarts, $limit);
            Logging::info(Authentication::getUser(),"Veritabanından Kullanıcılar Sayfası İçin Kullanıcılar Çekildi");
        return $data;
    }

    public function updateWithAPI(){
        $token = $_GET["token"];
        $repo = new UserRepository();
        $user = $repo->selectByToken($token);
        $user->setName($_POST["name"]);
        $user->setSurname($_POST["surname"]);
        $user->setEmail($_POST["email"]);
        if ($_POST["password"] != "")
        {
            $user->setPassword($_POST["password"]);
        }
        $result = $repo->update($user);
        if ($result[0] == 1)
        {
            Logging::info($user,"Veritabanında api ile profil bilgileri güncellendi");
            return true;
        }
        else
        {
            
            Logging::info($user,"Veritabanında api ile profil bilgileri güncellenirken bir hata oluştu.");
            return false;
        }
    }

    public function logOutForAPI(){
        if (Authentication::check())
        {
            Logging::info(Authentication::getUser(),"API ile sistemden çıkış yaptı.");
            $result = Authentication::logOut();
            return $result;
        }
    }

    public function addNewHistoryForAPI($user,$new){
        $repo = new UserRepository();
        $result = $repo->createNewHistory($user,$new);
        if ($result[0] == 1)
        {
            return true;
        }
        return false;
    }

    public function deleteUser(){
        $token = $_GET["token"];
        $repo = new UserRepository();
        $user = $repo->selectByToken($token);
        if ($user == false)
        {
            return false;
        }
        $result = $repo->addToDeleteList($user->getId());
        if ( $result[0] == 0)
        {
            Logging::critical($user,"Silinecek hesap eklenirken bir sorun oluştu.");
            return false;
        }
        Logging::info($user,"Hesap başarıyla silinecekler arasına eklendi.");
        return true;
    }

    public function getDeleteList($page){
        if (empty($page) || !is_numeric($page))
        {
            $page = 1;
        }
        $limit = 5;
        $repo = new UserRepository();
        $pageStarts = ($page*$limit) - $limit;
        $data = $repo->selectWaitingsPagination($pageStarts, $limit);
        Logging::info(Authentication::getUser(),"Veritabanından Silinecek KullanıcılarÇekildi");
        return $data;
    }

    public function deleteListCount(){
        $repo = new UserRepository();
        $data = $repo->selectDeleteList();
        Logging::info(Authentication::getUser(),"Veritabanından Silinecek Kullanıcıların Sayısı Çekildi");
        return $data;
    }

    public function deleteFromWaitings(){
        $id = $_GET["id"];
        $repo = new UserRepository();
        $result = $repo->deleteFromWaitings($id);
        if ($result == false){
            Logging::emergency(Authentication::getUser(),"Kullanıcı silme reddetmede sorun oluştu");
            return false;
        }
        Logging::info(Authentication::getUser(),"Kullanıcı silme talebi başarıyla reddedildi");
        return $result;
    }

    public function addUserRelatedCategory(){
        $token = $_GET["token"];
        $repo = new UserRepository();
        $user = $repo->selectByToken($token);
        if ($user == false){
            return false;
        }
        $repo->deleteRelatedCategory($user->getId());
        $relatedCategory = $_POST["relatedCategory"];
        if(count($relatedCategory) > 0) {
            foreach($relatedCategory as $catId) {
                $repo->addRelatedCategory($user->getId(), $catId);
                
            }
            Logging::info(Authentication::getUser(),count($relatedCategory)." adet kategori kullanıcının kategorileri eklendi");
            return true;
        }
        Logging::info(Authentication::getUser()," kullanıcıya kategor eklerken bir hata oluştu.");
        return false;

    }

    public function getRelatedCategories(){
        $token = $_GET["token"];
        $repo = new UserRepository();
        $user = $repo->selectByToken($token);
        if ($user == false){
            return false;
        }
        $catRepo = new CategoryRepository();
        $categories = $catRepo->select();
        $relatedCat = $repo->getRelatedCategoryById($user->getId());
        $result = [];
        foreach($categories as $category)
        {
            $check = false;
            foreach($relatedCat as $rc){
                if($rc["category"] == $category->getId())
                {
                    $check = true;
                }
            }
            $result[] = [
                "id" => $category->getId(),
                "category" => $category->getCategory(),
                "check"    => $check
            ];

        }
        Logging::info($user," kullanıcı kategorilerini çekti.");
        return $result;
    }
}