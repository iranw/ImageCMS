<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Image CMS
 * Module Wishlist
 * @property wishlist_model $wishlist_model
 */
class Wishlist extends \wishlist\classes\BaseWishlist {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    /**
     * index method
     */
    function index() {
        $this->core->set_meta_tags('Wishlist');
        $this->template->registerMeta("ROBOTS", "NOINDEX, NOFOLLOW");
        if ($this->dx_auth->is_logged_in()) {
            parent::getUserWL($this->dx_auth->get_user_id());
            \CMSFactory\assetManager::create()
                    ->registerScript('wishlist')
                    ->registerStyle('style')
                    ->setData('wishlists', $this->dataModel['wishlists'])
                    ->setData('user', $this->dataModel['user'])
                    ->setData('settings', $this->settings)
                    ->setData('errors', $this->errors)
                    ->render('wishlist');
        }
        else
            $this->core->error_404();
    }

    /**
     * add item to wishlist
     * @param type $varId
     */
    public function addItem($varId) {
        parent::addItem($varId);
        if ($this->dataModel) {
            redirect($this->input->cookie('url'));
        } else {
            \CMSFactory\assetManager::create()
                    ->registerScript('wishlist')
                    ->setData('errors', $this->errors)
                    ->render('errors');
        }
    }

    /**
     * move item to another wishlist
     * @param type $varId
     * @param type $wish_list_id
     */
    public function moveItem($varId, $wish_list_id) {
        parent::moveItem($varId, $wish_list_id);
        if ($this->dataModel) {
            redirect('/wishlist');
        } else {
            \CMSFactory\assetManager::create()
                    ->setData('errors', $this->errors)
                    ->render('errors');
        }
    }

    /**
     * get all wishlist
     */
    public function all() {
        $lists = parent::all();
        if ($this->dataModel) {
            \CMSFactory\assetManager::create()
                    ->setData('lists', $lists)
                    ->setData('settings', $this->settings)
                    ->render('all');
        } else {
            \CMSFactory\assetManager::create()
                    ->setData('errors', $this->errors)
                    ->setData('settings', $this->settings)
                    ->render('all');
        }
    }

    /**
     * show wishlist by hash
     * @param type $hash
     */
    public function show($hash) {
        if (parent::show($hash)) {
            \CMSFactory\assetManager::create()
                    ->setData('wishlist', $this->dataModel)
                    ->render('other_list');
        } else {
            \CMSFactory\assetManager::create()
                    ->setData('wishlist', 'empty')
                    ->render('other_list');
        }
    }

    /**
     * get most viewed wishlists
     * @param type $limit
     * @return mixed
     */
    public function getMostViewedWishLists($limit = 10) {
        parent::getMostViewedWishLists($limit);
        if ($this->dataModel) {
            return $this->dataModel;
        } else {
            return $this->errors;
        }
    }

    /**
     * get user by user id
     * @param type $user_id
     */
    public function user($user_id) {
        $user_wish_lists = parent::user($user_id);
        \CMSFactory\assetManager::create()
                ->setData('wishlists', $user_wish_lists)
                ->render('other_wishlist');
    }

    /**
     * update user data
     * @return mixed
     */
    public function userUpdate() {
        parent::userUpdate();
        if ($this->dataModel) {
            redirect('/wishlist');
        } else {
            return $this->errors;
        }
    }

    /**
     * get most popylar items
     * @param type $limit
     * @return mixed
     */
    public function getMostPopularItems($limit = 10) {
        parent::getMostPopularItems($limit);
        if ($this->dataModel) {
            return $this->dataModel;
        } else {
            return $this->errors;
        }
    }

    /**
     * create wishlist
     * @return mixed
     */
    public function createWishList() {
        parent::createWishList();
        if ($this->dataModel) {
            return $this->dataModel;
        } else {
            foreach ($this->errors as $error)
                echo $error;
        }
    }

    /**
     * renser button for add to wishlist
     * @param type $varId
     */
    public function renderWLButton($varId) {
        if ($this->dx_auth->is_logged_in()) {
            $href = '/wishlist/renderPopup/' . $varId;
        } else {
            $href = '/auth/login';
        }

        if (!in_array($varId, $this->userWishProducts))
            \CMSFactory\assetManager::create()
                    ->registerScript('wishlist')
                    ->setData('data', $data)
                    ->setData('varId', $varId)
                    ->setData('value', lang('btn_add_2_WL'))
                    ->setData('class', 'btn')
                    ->setData('href', $href)
                    ->setData('max_lists_count', $this->settings['maxListsCount'])
                    ->render('button', true);
        else
            \CMSFactory\assetManager::create()
                    ->registerScript('wishlist')
                    ->setData('data', $data)
                    ->setData('varId', $varId)
                    ->setData('href', $href)
                    ->setData('value', lang('btn_already_in_WL'))
                    ->setData('max_lists_count', $this->settings['maxListsCount'])
                    ->setData('class', 'btn inWL')
                    ->render('button', true);
    }

    /**
     * render popup for adding to wishlist
     * @param type $varId
     * @param type $wish_list_id
     * @return mixed
     */
    public function renderPopup($varId, $wish_list_id = '') {
        $wish_lists = $this->wishlist_model->getWishLists();
        $data = array('wish_lists' => $wish_lists);

        return $popup = \CMSFactory\assetManager::create()
                ->registerStyle('style')
                ->setData('class', 'btn')
                ->setData('wish_list_id', $wish_list_id)
                ->setData('varId', $varId)
                ->setData($data)
                ->setData('max_lists_count', $this->settings['maxListsCount'])
                ->render('wishPopup');
    }

    /**
     * edit wish list
     * 
     * @param int $wish_list_id
     */
    public function editWL($wish_list_id) {
        if (parent::renderUserWLEdit($wish_list_id))
            \CMSFactory\assetManager::create()
                    ->registerScript('wishlist')
                    ->registerStyle('style')
                    ->setData('wishlists', $this->dataModel)
                    ->render('wishlistEdit');
        else
            redirect('/wishlist');
    }

    /**
     * update wish list
     * 
     */
    public function updateWL() {
        parent::updateWL();
        redirect('/wishlist');
    }

    /**
     * delete wish list
     * 
     * @param int $wish_list_id
     */
    public function deleteWL($wish_list_id) {
        parent::deleteWL($wish_list_id);
        redirect('/wishlist');
    }

    /**
     * delete item from wish list
     * 
     * @param int $variant_id
     * @param int $wish_list_id
     * @return mixed
     */
    public function deleteItem($variant_id, $wish_list_id) {
        parent::deleteItem($variant_id, $wish_list_id);
        if ($this->dataModel) {
            redirect('/wishlist');
        } else {
            return $this->errors;
        }
    }

    /**
     * delete items by ids
     * 
     * @return mixed
     */
    public function deleteItemsByIds() {
        parent::deleteItemsByIds();
        if ($this->dataModel) {
            redirect('/wishlist');
        } else {
            return $this->errors;
        }
    }

    /**
     * delete user image
     * 
     * @return mixed
     */
    public function deleteImage() {
        parent::deleteImage();
        if ($this->dataModel) {
            return $this->dataModel;
        } else {
            return $this->errors;
        }
    }

    /**
     * upload user image
     */
    public function do_upload() {
        parent::do_upload();
        redirect('/wishlist');
    }

}

/* End of file wishlist.php */
