<?php
namespace CashbackApi\Reseller;


/**
 * Class Users
 * @package CashbackApi\Reseller
 */
class Users extends BaseReseller
{

    /**
     * @param $userId
     * @param null $user
     * @return bool|object
     */
    public function update($userId, $user = null)
    {

        $data = new \stdClass();

        $data->username = null;
        $data->email = null;
        $data->password = null;
        $data->skype = null;
        $data->mobile = null;
        $data->profile_picture = null;
        $data->manage_retailers = null;
        $data->manage_reseller_users = null;
        $data->manage_whitelabels = null;
        $data->approve_retailer_changes = null;
        $data->manage_categories = null;

        if (isset($user) && is_object($user)) {
            foreach ($user as $key => $value) {
                if (property_exists($data, $key)) {
                    $data->$key = $value;
                }
            }
        }

        $data->user_id = (int)$userId;

        return $this->doRequest('reseller/users/update', $data);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function uploadProfilePic($userId)
    {
        if (!(isset($userId) && is_numeric($userId) && $userId)) {
            $this->setLastErrorMessage('User ID Required!');
            return false;
        }
        if (isset($_FILES['profile_pic'])) {
            $data = new \stdClass();
            $data->user_id = (int)$userId;
            $this->setFiles($_FILES);
            return $this->doUpload('reseller/users/upload-profile-pic', $data);


        } else {
            $this->setLastErrorMessage('profile_pic not specified as file in $_FILES array!');
        }
        return false;
    }

    public function updateProfilePic($userId, $profilePic)
    {
        $data = new \stdClass();
        $data->profile_pic = $profilePic;
        $data->user_id = $userId;

        return $this->doRequest('reseller/users/update-profile-pic', $data);
    }

    /**
     * @return bool|object
     */
    public function getAll()
    {
        return $this->doRequest('reseller/users/get-all');
    }

    /**
     * @param $userId
     * @return bool|object
     */
    public function getUser($userId)
    {
        $data = new \stdClass();
        $data->user_id = (int)$userId;
        return $this->doRequest('reseller/users/get', $data);
    }

    public function createUser($user)
    {
        $data = new \stdClass();

        $data->username = null;
        $data->email = null;
        $data->password = null;
        $data->skype = null;
        $data->mobile = null;
        $data->manage_whitelabels = false;
        $data->manage_reseller_users = false;
        $data->manage_retailers = false;
        $data->approve_retailer_changes = false;
        $data->manage_categories = false;

        $this->mapData($data, $user);

        return $this->doRequest('reseller/users/create', $data);
    }

    public function checkPassword($userId, $password)
    {
        $data = new \stdClass();
        $data->password = $password;
        $data->user_id = (int)$userId;

        return $this->doRequest('reseller/users/check-password', $data);
    }

    /**
     * @param $userId
     * @return bool|object
     */
    public function deactivate($userId)
    {
        if (isset($userId)) {
            $data = new \stdClass();
            $data->user_id = $userId;
            return $this->doRequest('reseller/users/deactivate', $data);
        }
    }

    /**
     * @param $userId
     * @return bool|object
     */
    public function activate($userId)
    {

        if (isset($userId)) {
            $data = new \stdClass();
            $data->user_id = $userId;

            return $this->doRequest('reseller/users/activate', $data);
        }

    }

    public function getProfileImages($userId = null)
    {

        $userPath = (isset($userId)) ? '/' . $userId : '';
        $path = 'profile_pics' . $userPath;
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
}