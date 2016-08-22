<?php
/**
 * NOTICE OF LICENSE.
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Tappz Team
 *  @copyright 2009-2016 Tmob
 *  @license   LICENSE.txt
 */

class CategoryService extends BaseService
{
    public function getBlockTopMenu()
    {
        $categories = array();
        $menu_item = $this->getMenuItems();
        $id_lang = (int) $this->getLangId();
        foreach ($menu_item as $item) {
            if (!$item) {
                continue;
            }
            preg_match('/^([A-Z_]*)[0-9]+/', $item, $values);
            $id = (int) Tools::substr($item, Tools::strlen($values[1]), Tools::strlen($item));
            if (Tools::substr($item, 0, Tools::strlen($values[1])) == 'CAT') {
                $category = new Category((int) $id, (int) $id_lang);
                if (Validate::isLoadedObject($category)) {
                    $childrens = Category::getChildren($category->id, $this->getLangId(), true);
                    $child = array();
                    if (sizeof($childrens) > 0) {
                        $isLeaf = false;
                        foreach ($childrens as $children) {
                            $childs = new Category($children['id_category'], $this->getLangId());
                            $child[] = self::categoryBuilder(
                                $childs->id,
                                $childs->name,
                                true,
                                false,
                                $childs->id_parent,
                                array(),
                                $childs->description
                            );
                        }
                    } else {
                        $isLeaf = true;
                    }
                    $categories[] = self::categoryBuilder(
                        $category->id,
                        $category->name,
                        true,
                        $isLeaf,
                        0,
                        $child,
                        $category->description
                    );
                }
            }
        }

        return (array) $categories;
    }

    public function getCategories()
    {
        $blocktopmenu = Module::isEnabled('blocktopmenu');
        if ($blocktopmenu) {
            $categories = $this->getBlockTopMenu();
            $this->response($categories);
        } else {
            $categories = array();
            $parent = array();
            $root = Category::getRootCategory();
            $rows = Category::getCategories($this->getLangId(), true);

            foreach ($rows as $id => $row) {
                foreach ($row as $cat_id => $item) {
                    $child = array();
                    if ($item['infos']['level_depth'] > $root->level_depth) {
                        $id == $root->id || $parent[$id] = true;
                        $childrens = Category::getChildren($cat_id, $this->getLangId(), true);
                        if (sizeof($childrens) > 0) {
                            foreach ($childrens as $children) {
                                $childs = new Category($children['id_category'], $this->getLangId());
                                $isRoot = $childs->id == $root->id ? true : false;
                                $child[] = self::categoryBuilder(
                                    $childs->id,
                                    $childs->name,
                                    $isRoot,
                                    false,
                                    $childs->id_parent,
                                    array(),
                                    $childs->description
                                );
                            }
                        }
                        $countCat = self::isParentCategory($cat_id, $root->level_depth);
                        sizeof($countCat) > 0 ? $parent_id = $countCat[0]['id_parent'] : $parent_id = 0;
                        sizeof($child) > 0 ? $isRoot = true : $isRoot = false;
                        sizeof($child) > 0 ? $isLeaf = true : $isLeaf = false;
                        $categories[] = self::categoryBuilder(
                            $cat_id,
                            $item['infos']['name'],
                            $isRoot,
                            $isLeaf,
                            $parent_id,
                            $child,
                            $item['infos']['description']
                        );
                    }
                }
            }
            $this->response($categories);
        }
    }

    protected function getMenuItems()
    {
        $items = Tools::getValue('items');
        if (is_array($items) && count($items)) {
            return $items;
        } else {
            $shops = Shop::getContextListShopID();
            $conf = null;

            if (count($shops) > 1) {
                foreach ($shops as $key => $shop_id) {
                    $shop_group_id = Shop::getGroupFromShop($shop_id);
                    $topItem = Configuration::get('MOD_BLOCKTOPMENU_ITEMS', null, $shop_group_id, $shop_id);
                    $conf .= (string) ($key > 1 ? ',' : '').$topItem;
                }
            } else {
                $shop_id = (int) $shops[0];
                $shop_group_id = Shop::getGroupFromShop($shop_id);
                $conf = Configuration::get('MOD_BLOCKTOPMENU_ITEMS', null, $shop_group_id, $shop_id);
            }

            if (Tools::strlen($conf)) {
                return explode(',', $conf);
            } else {
                return array();
            }
        }
    }

    public function getCategoryById($id)
    {
        $menu_item = $this->getMenuItems();
        $menu_id = array();
        foreach ($menu_item as $item) {
            if (!$item) {
                continue;
            }
            preg_match('/^([A-Z_]*)[0-9]+/', $item, $values);
            $menu_id[] = (int) Tools::substr($item, Tools::strlen($values[1]), Tools::strlen($item));
        }
        $root = Category::getRootCategory();
        $category = new Category($id, $this->getLangId());
        if ($category->level_depth > $root->level_depth) {
            $id == $root->id;
            $child = array();
            $childrens = Category::getChildren($category->id, $this->getLangId(), true);
            if (sizeof($childrens) > 0) {
                foreach ($childrens as $children) {
                    $childs = new Category($children['id_category'], $this->getLangId());
                    $isRoot = in_array($childs->id, $menu_id) ? true : false;
                    sizeof($child) > 0 ? $isLeaf = false : $isLeaf = true;
                    $child[] = self::categoryBuilder(
                        $childs->id,
                        $childs->name,
                        $isRoot,
                        $isLeaf,
                        $childs->id_parent,
                        array(),
                        $childs->description
                    );
                }
            }
            $countParentCategory = self::isParentCategory($category->id, $root->level_depth);
            sizeof($countParentCategory) > 0 ? $parent_id = $countParentCategory[0]['id_parent'] : $parent_id = 0;
            $id == $root->id ? $isRoot = true : $isRoot = false;
            sizeof($child) > 0 ? $isLeaf = false : $isLeaf = true;
            $isRoot = in_array($category->id, $menu_id) ? true : false;
            $result = self::categoryBuilder(
                $category->id,
                $category->name,
                $isRoot,
                $isLeaf,
                $parent_id,
                $child,
                $category->description
            );
        }
        $this->response($result);
    }

    private function categoryBuilder(
        $id,
        $name,
        $isRoot,
        $isLeaf,
        $parent_id,
        $children,
        $description,
        $errorCode = null,
        $message = null,
        $userFriendly = false
    ) {
        return array(
            'id' => $id,
            'name' => $name,
            'isRoot' => $isRoot,
            'isLeaf' => $isLeaf,
            'parentCategoryId' => $parent_id,
            'children' => $children,
            'description' => $description,
            'ErrorCode' => $errorCode,
            'Message' => $message,
            'UserFriendly' => $userFriendly,
        );
    }

    private function isParentCategory($id, $level_depth)
    {
        $query = "
                SELECT  c.id_parent
                  FROM "._DB_PREFIX_."category c
                WHERE id_parent > 2 AND level_depth >'".pSQL($level_depth)."' AND c.id_category = '".(int) ($id)."'";

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);
    }

    private function getCMSCategories($recursive = false, $parent = 1, $id_lang = false, $id_shop = false)
    {
        $id_lang = (int)$id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;
        $id_shop = (int) ($id_shop !== false) ? $id_shop : Context::getContext()->shop->id;
        $join_shop = '';
        $where_shop = '';

        if (Tools::version_compare(_PS_VERSION_, '1.6.0.12', '>=') == true) {
            $join_shop = ' INNER JOIN `'._DB_PREFIX_.'cms_category_shop` cs
			ON (bcp.`id_cms_category` = cs.`id_cms_category`)';
            $where_shop = ' AND cs.`id_shop` = '.pSQL($id_shop).' AND cl.`id_shop` = '.pSQL($id_shop);
        }
        $parent  = (int) $parent;
        if ($recursive === false) {
            $sql = 'SELECT
                          bcp.`id_cms_category`,
                          bcp.`id_parent`,
                          bcp.`level_depth`,
                          bcp.`active`, bcp.`position`,
                          cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp'.
                $join_shop.'
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '. pSQL($id_lang).'
				AND bcp.`id_parent` = '.pSQL($parent).
                $where_shop;

            return Db::getInstance()->executeS($sql);
        } else {
            $sql = 'SELECT bcp.`id_cms_category`,
                           bcp.`id_parent`,
                           bcp.`level_depth`,
                           bcp.`active`,
                           bcp.`position`,
                           cl.`name`,
                           cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp'.
                $join_shop.'
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.pSQL($id_lang).'
				AND bcp.`id_parent` = '.pSQL($parent).
                $where_shop;

            $results = Db::getInstance()->executeS($sql);
            $categories = array();
            foreach ($results as $result) {
                $sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], $id_lang);
                if ($sub_categories && count($sub_categories) > 0) {
                    $result['sub_categories'] = $sub_categories;
                }
                $categories[] = $result;
            }

            return Tools::getIsset($categories) ? $categories : false;
        }
    }
}
