<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: TDMCreateTableFields.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreateTableFields
 */
class TDMCreateTableFields extends TDMCreateAbstract
{
    /**
     *  @public function constructor
     *  @param mixed $table
     */
    public function __construct()
    {
        $this->tdmcreate = TDMCreateHelper::getInstance();
    }

    /**
     *  @public function getTableFields
     *  @param $tableId
     *  @return mixed
     */
    public function getTableFields($tableId)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('field_tid', $tableId));
        $fields = $this->tdmcreate->getHandler('fields')->getObjects($criteria);
        unset($criteria);

        return $fields;
    }
}
