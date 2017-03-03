<?php

namespace CashbackApi\Reseller;


use CashbackApi\BaseApi;

/**
 * Class User
 * @package CashbackApi\Reseller
 */
class User extends BaseReseller
{
    /**
     * @var null|object
     */
    private static $user = null;
    /**
     * @var null|callable
     */
    private static $userStorageFunction = null;
    /**
     * @var null|callable
     */
    private static $userRetrievalFunction = null;


    /**
     * @return null
     */
    public function getUser($fromStorage = true)
    {
        if ($fromStorage && is_callable(self::$userRetrievalFunction)) {
            $user = call_user_func(self::$userRetrievalFunction);
            $this->setUser($user, false);
            return $this->getUser(false);
        }

        if (isset(self::$user) && is_object(self::$user)) {
            return self::$user;
        } else {
            $user = $this->doRequest('reseller/user/get');
            if ($user) {
                $this->setUser($user);
                return $user;
            }

        }
        return false;
    }


    /**
     * @param $Function
     */
    public function setUserStorageFunction($Function)
    {
        self::$userStorageFunction = $Function;
    }

    /**
     * @param $Function
     */
    public function setUserRetrievalFunction($Function)
    {
        self::$userRetrievalFunction = $Function;
    }

    /**
     * @param $user
     * @return bool|mixed
     */
    public function runUserStorageFunction($user)
    {
        if (is_callable(self::$userStorageFunction)) {
            return call_user_func_array(self::$userStorageFunction, array($user));
        }
        return false;
    }


    /**
     * @param null $user
     */
    private function setUser($user = null, $toStorage = true)
    {
        if (isset($user) && is_object($user)) {
            if ($toStorage) {
                $this->runUserStorageFunction($user);
            }
            self::$user = $user;
        }
    }

    protected function destroyUserSession()
    {
        self::$user = null;
        $this->runUserStorageFunction(null);
    }

    /**
     * @param null $username
     * @param null $password
     * @param null $email
     * @return bool|object
     */
    public function login($username = null, $password = null, $email = null)
    {

        $data = new \stdClass();

        $data->username = $username;
        $data->email = $email;
        $data->password = $password;
        /**
         * TODO deal with return session
         */
        return $this->doRequest('reseller/user/login', $data);
    }

    /**
     * @return bool|object
     */
    public function logout()
    {
        /**
         * TODO deal with return session
         */
        $alteredSession = $this->doRequest('reseller/user/logout');
        if ($alteredSession) {
            $this->destroyUserSession();
            return true;
        }

        return false;
    }

    /**
     * $_FILES['profile_pic'] needs to be set!
     * @return bool
     */
    public function uploadProfilePic()
    {
        if (isset($_FILES['profile_pic'])) {
            $this->setFiles($_FILES);
            $amendedUser = $this->doUpload('reseller/user/upload-profile-pic');
            if ($amendedUser) {
                $this->setUser($amendedUser);
            }
            return $amendedUser;
        } else {
            $this->setLastErrorMessage('profile_pic not specified as file!');
        }
        return false;
    }

    /**
     * @param $profilePic
     * @return bool|object
     */
    public function updateProfilePic($profilePic)
    {
        $data = new \stdClass();
        $data->profile_pic = $profilePic;

        $amendedUser = $this->doRequest('reseller/user/update-profile-pic', $data);
        if ($amendedUser) {
            $this->setUser($amendedUser);
        }

        return $amendedUser;
    }

    public function update($user = null)
    {

        $data = new \stdClass();

        $data->username = null;
        $data->email = null;
        $data->password = null;
        $data->skype = null;
        $data->mobile = null;
        $data->profile_picture = null;

        $this->mapData($data, $user);

        $amendedUser = $this->doRequest('reseller/user/update', $data);
        if ($amendedUser) {
            $this->setUser($amendedUser);
        }

        return $amendedUser;
    }

    public function getUsername()
    {

        $user = $this->getUser();
        if (isset($user->username)) {
            return $user->username;
        }
        return '';
    }

    public function getProfilePicture()
    {

        $user = $this->getUser();
        if (isset($user->profile_picture)) {
            return $user->profile_picture;
        }

        return '';
    }

    public function checkPassword($password)
    {
        $data = new \stdClass();
        $data->password = $password;
        return $this->doRequest('reseller/user/check-password', $data);
    }

    public function deactivate()
    {
        return $this->doRequest('reseller/user/deactivate');

    }

    public function getProfileImages()
    {
        $user = $this->getUser();
        if (isset($user->user_id)) {
            $path = 'profile_pics/' . $user->user_id;
            $images = $this->getMedia()->getImages($path);

            if ($images) {
                foreach ($images as $image) {

                    if (isset($image->meta_data) && isset($image->meta_data->thumb_src)) {
                        $image->src = $image->meta_data->thumb_src;
                    }
                }
            }
            return $images;
        }
        return false;
    }

}