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
 * tdmcreatereate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 * @version         $Id: 1.91 fields.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
include __DIR__.'/autoload.php';
/*
*  @Class TDMCreateFields
*  @extends XoopsObject
*/

/**
 * Class TDMCreateFields.
 */
class TDMCreateFields extends XoopsObject
{
    /**
     * @var mixed
     */
    private $tdmcreate;

    /**
     * optionFields.
     */
    public $optionsFields = array(
        'parent',
        'admin',
        'inlist',
        'inform',
        'user',
        'thead',
        'tbody',
        'tfoot',
        'block',
        'search',
        'required',
    );

    /*
    *  @public function constructor class
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->tdmcreate = TDMCreateHelper::getInstance();
        $this->initVar('field_id', XOBJ_DTYPE_INT);
        $this->initVar('field_mid', XOBJ_DTYPE_INT);
        $this->initVar('field_tid', XOBJ_DTYPE_INT);
        $this->initVar('field_order', XOBJ_DTYPE_INT);
        $this->initVar('field_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_type', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_value', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_attribute', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_null', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_default', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_key', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_element', XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_parent', XOBJ_DTYPE_INT);
        $this->initVar('field_admin', XOBJ_DTYPE_INT);
        $this->initVar('field_inlist', XOBJ_DTYPE_INT);
        $this->initVar('field_inform', XOBJ_DTYPE_INT);
        $this->initVar('field_user', XOBJ_DTYPE_INT);
        $this->initVar('field_thead', XOBJ_DTYPE_INT);
        $this->initVar('field_tbody', XOBJ_DTYPE_INT);
        $this->initVar('field_tfoot', XOBJ_DTYPE_INT);
        $this->initVar('field_block', XOBJ_DTYPE_INT);
        $this->initVar('field_main', XOBJ_DTYPE_INT);
        $this->initVar('field_search', XOBJ_DTYPE_INT);
        $this->initVar('field_required', XOBJ_DTYPE_INT);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $arg = isset($args[0]) ? $args[0] : null;

        return $this->getVar($method, $arg);
    }

    /*
     * @static function &getInstance
     *
     * @return TDMCreateFields
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
     * @private function getHeaderForm
     *
     * @param bool $action
     * @return TDMCreateThemeForm
     */
    private function getHeaderForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $isNew = $this->isNew();
        $title = $isNew ? sprintf(_AM_TDMCREATE_FIELDS_NEW) : sprintf(_AM_TDMCREATE_FIELDS_EDIT);

        $form = new TDMCreateThemeForm(null, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        // New Object HtmlTable
        $form->addElement(new TDMCreateFormLabel('<table cellspacing="1" class="outer width100">'));
        $form->addElement(new TDMCreateFormLabel('<thead class="center">'));
        $form->addElement(new TDMCreateFormLabel('<tr class="head"><th colspan="9">'.$title.'</th></tr>'));
        $form->addElement(new TDMCreateFormLabel('<tr class="head width5">'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_ID.'</td>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_NAME.'</td>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_TYPE.'</td>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_VALUE.'</th>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_ATTRIBUTE.'</th>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_NULL.'</th>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_DEFAULT.'</th>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_KEY.'</th>'));
        $form->addElement(new TDMCreateFormLabel('<td>'._AM_TDMCREATE_FIELD_PARAMETERS.'</th>'));
        $form->addElement(new TDMCreateFormLabel('</tr></thead>'));
        $form->addElement(new TDMCreateFormLabel('<tbody>'));

        //
        return $form;
    }

    /*
     * @public function getFormNew
     *
     * @param null $fieldMid
     * @param null $fieldTid
     * @param null $fieldNumb
     * @param null $fieldName
     * @param bool $action
     * @return mixed
     */
    public function getFormNew($fieldMid = null, $fieldTid = null, $fieldNumb = null, $fieldName = null, $action = false)
    {
        // Header function class
        $fieldsForm = self::getInstance();
        $form = $fieldsForm->getHeaderForm($action);
        // Get handler tables
        $tableObj = $this->tdmcreate->getHandler('tables'); // Changed by goffy
        $tableAutoincrement = $tableObj->get($fieldTid)->getVar('table_autoincrement'); // Added by goffy
        // Loop for fields number
        $class = 'even';
        for ($i = 1; $i <= $fieldNumb; ++$i) {
            $class = ($class == 'even') ? 'odd' : 'even';
            // Replaced creation of new line by new function - goffy
            $this->getFormNewLine($form, $class, $i, $fieldMid, $fieldTid, $fieldName, $tableAutoincrement);
        }

        // Footer form
        return $fieldsForm->getFooterForm($form);
    }

    /**
     * @private function getFormNewLine
     *
     * @param $form
     * @param $class
     * @param $i
     * @param $fieldMid
     * @param $fieldTid
     * @param $fName
     * @param $tableAutoincrement
     */
    private function getFormNewLine($form, $class, $i, $fieldMid, $fieldTid, $fName, $tableAutoincrement)
    {
        $form->addElement(new XoopsFormHidden('field_id['.$i.']', 0));
        $form->addElement(new XoopsFormHidden('field_mid', $fieldMid));
        $form->addElement(new XoopsFormHidden('field_tid', $fieldTid));

        $form->addElement(new TDMCreateFormLabel('<tr class="'.$class.'">'));
        // Index ID
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$i.'</td>'));
        // Field Name
        $thisFieldName = (!empty($fName) ? ((1 == $i) ? $fName.'_id' : $fName.'_') : '');
        $fieldName = new XoopsFormText(_AM_TDMCREATE_FIELD_NAME, 'field_name['.$i.']', 15, 255, $thisFieldName);
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldName->render().'</td>'));
        // Field Type
        $value = (1 == $i) && (1 == $tableAutoincrement) ? '2' : '';
        $fieldTypeSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_type['.$i.']', $value);
        $fieldTypeSelect->addOptionArray($this->tdmcreate->getHandler('fieldtype')->getList());
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldTypeSelect->render().'</td>'));
        // Field Value
        $value = (1 == $i) && (1 == $tableAutoincrement) ? '8' : '';
        $fieldValue = new XoopsFormText(_AM_TDMCREATE_FIELD_VALUE, 'field_value['.$i.']', 5, 20, $value);
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldValue->render().'</td>'));
        // Field Attributes
        $value = (1 == $i) && (1 == $tableAutoincrement) ? '3' : '';
        $fieldAttributesSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_attribute['.$i.']', $value);
        $fieldAttributesSelect->addOptionArray($this->tdmcreate->getHandler('fieldattributes')->getList());
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldAttributesSelect->render().'</td>'));
        // Field Null
        $value = (1 == $i) && (1 == $tableAutoincrement) ? '2' : '2';
        $fieldNullSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_NULL, 'field_null['.$i.']', $value);
        $fieldNullSelect->addOptionArray($this->tdmcreate->getHandler('fieldnull')->getList());
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldNullSelect->render().'</td>'));
        // Field Default
        $fieldDefault = new XoopsFormText(_AM_TDMCREATE_FIELD_DEFAULT, 'field_default['.$i.']', 15, 25);
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldDefault->render().'</td>'));
        // Field Key
        $value = (1 == $i) && (1 == $tableAutoincrement) ? '2' : '';
        $fieldKeySelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_KEY, 'field_key['.$i.']', $value);
        $fieldKeySelect->addOptionArray($this->tdmcreate->getHandler('fieldkey')->getList());
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldKeySelect->render().'</td>'));
        // Field Void
        if ((1 == $i) && (1 == $tableAutoincrement)) {
            $form->addElement(new TDMCreateFormLabel('<td>&nbsp;</td></tr>'));
        } else {
            // Box header row
            $parametersTray = new XoopsFormElementTray('', '<br />');
            // Field Elements
            $criteriaElement = new CriteriaCompo();
            $criteriaElement->add(new Criteria('fieldelement_tid', 0));
            $criteriaTable = new CriteriaCompo();
            $criteriaTable->add(new Criteria('fieldelement_mid', $fieldMid));
            $fieldElementsSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_ELEMENT_NAME, 'field_element['.$i.']');
            $fieldElementsSelect->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteriaElement));
            $fieldElementsSelect->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteriaTable));
            unset($criteriaElement, $criteriaTable);
            $parametersTray->addElement($fieldElementsSelect);

            $field_parent = 0;
            $checkFieldParent = new XoopsFormCheckBox(' ', 'field_parent['.$i.']', $field_parent);
            $checkFieldParent->addOption(1, _AM_TDMCREATE_FIELD_PARENT);
            $parametersTray->addElement($checkFieldParent);

            $field_admin = 0;
            $checkFieldAdmin = new XoopsFormCheckBox(' ', 'field_admin['.$i.']', $field_admin);
            $checkFieldAdmin->addOption(1, _AM_TDMCREATE_FIELD_ADMIN);
            $parametersTray->addElement($checkFieldAdmin);

            $field_inlist = 0;
            $checkFieldInList = new XoopsFormCheckBox(' ', 'field_inlist['.$i.']', $field_inlist);
            $checkFieldInList->addOption(1, _AM_TDMCREATE_FIELD_INLIST);
            $parametersTray->addElement($checkFieldInList);

            $field_inform = 0;
            $checkFieldInForm = new XoopsFormCheckBox(' ', 'field_inform['.$i.']', $field_inform);
            $checkFieldInForm->addOption(1, _AM_TDMCREATE_FIELD_INFORM);
            $parametersTray->addElement($checkFieldInForm);

            $field_user = 0;
            $checkFieldUser = new XoopsFormCheckBox(' ', 'field_user['.$i.']', $field_user);
            $checkFieldUser->addOption(1, _AM_TDMCREATE_FIELD_USER);
            $parametersTray->addElement($checkFieldUser);

            $field_thead = 0;
            $checkFieldThead = new XoopsFormCheckBox(' ', 'field_thead['.$i.']', $field_thead);
            $checkFieldThead->addOption(1, _AM_TDMCREATE_FIELD_THEAD);
            $parametersTray->addElement($checkFieldThead);

            $field_tbody = 0;
            $checkFieldTbody = new XoopsFormCheckBox(' ', 'field_tbody['.$i.']', $field_tbody);
            $checkFieldTbody->addOption(1, _AM_TDMCREATE_FIELD_TBODY);
            $parametersTray->addElement($checkFieldTbody);

            $field_tfoot = 0;
            $checkFieldTfoot = new XoopsFormCheckBox(' ', 'field_tfoot['.$i.']', $field_tfoot);
            $checkFieldTfoot->addOption(1, _AM_TDMCREATE_FIELD_TFOOT);
            $parametersTray->addElement($checkFieldTfoot);

            $field_block = 0;
            $checkFieldBlock = new XoopsFormCheckBox('', 'field_block['.$i.']', $field_block);
            $checkFieldBlock->addOption(1, _AM_TDMCREATE_FIELD_BLOCK);
            $parametersTray->addElement($checkFieldBlock);

            $field_search = 0;
            $check_field_search = new XoopsFormCheckBox(' ', 'field_search['.$i.']', $field_search);
            $check_field_search->addOption(1, _AM_TDMCREATE_FIELD_SEARCH);
            $parametersTray->addElement($check_field_search);

            $field_required = 0;
            $checkFieldRequired = new XoopsFormCheckBox(' ', 'field_required['.$i.']', $field_required);
            $checkFieldRequired->addOption(1, _AM_TDMCREATE_FIELD_REQUIRED);
            $parametersTray->addElement($checkFieldRequired);

            $fieldMain = (1 == $tableAutoincrement) ? 2 : 1;
            $checkFieldMain = new TDMCreateFormRadio('', 'field_main', $fieldMain);
            $checkFieldMain->addOption($i, _AM_TDMCREATE_FIELD_MAIN);
            $parametersTray->addElement($checkFieldMain);
            //
            $form->addElement(new TDMCreateFormLabel('<td><div class="portlet"><div class="portlet-header">'._AM_TDMCREATE_FIELD_PARAMETERS_LIST.'</div><div class="portlet-content">'.$parametersTray->render().'</div></div></td></tr>'));
        }
    }

    /*
     * @public function getFormEdit
     *
     * @param null $fieldMid
     * @param null $fieldTid
     * @param bool $action
     * @return mixed
     */
    public function getFormEdit($fieldMid = null, $fieldTid = null, $action = false)
    {
        // Header function class
        $fieldsForm = self::getInstance();
        $form = $fieldsForm->getHeaderForm($action);

        $class = 'even';
        // Get the number of fields - goffy
        $tablesHandler = &$this->tdmcreate->getHandler('tables');
        $tables = $tablesHandler->get($fieldTid);
        $tableAutoincrement = $tables->getVar('table_autoincrement');
        $fieldNumb = $tables->getVar('table_nbfields');
        $fName = $tables->getVar('table_fieldname');

        // Get the list of fields
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('field_mid', $fieldMid));
        $criteria->add(new Criteria('field_tid', $fieldTid));
        $criteria->setSort('field_id'); //added by goffy
        $fields = $this->tdmcreate->getHandler('fields')->getObjects($criteria);
        unset($criteria);
        $id = 1;
        foreach ($fields as $field) {
            $class = ($class == 'even') ? 'odd' : 'even';
            $fieldId = (int) ($field->getVar('field_id'));
            if ($id > $fieldNumb) {   // delete additional fields, if number of fields is reduced - goffy
                $fieldsObj = &$this->tdmcreate->getHandler('fields')->get($fieldId);
                $this->tdmcreate->getHandler('fields')->delete($fieldsObj, true);
            } else {
                // show field with settings
                $form->addElement(new XoopsFormHidden('field_id['.$id.']', $fieldId));

                $form->addElement(new TDMCreateFormLabel('<tr class="'.$class.'">'));
                // Index ID
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$id.'</td>'));
                // Field Name
                $fieldName = new XoopsFormText(_AM_TDMCREATE_FIELD_NAME, 'field_name['.$id.']', 15, 255, $field->getVar('field_name'));
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldName->render().'</td>'));
                // Field Type
                $fieldTypeSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_type['.$id.']', $field->getVar('field_type'));
                $fieldTypeSelect->addOptionArray($this->tdmcreate->getHandler('fieldtype')->getList());
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldTypeSelect->render().'</td>'));
                // Field Value
                $fieldValue = new XoopsFormText(_AM_TDMCREATE_FIELD_VALUE, 'field_value['.$id.']', 5, 20, $field->getVar('field_value'));
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldValue->render().'</td>'));
                // Field Attributes
                $fieldAttributesSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_attribute['.$id.']', $field->getVar('field_attribute'));
                $fieldAttributesSelect->addOptionArray($this->tdmcreate->getHandler('fieldattributes')->getList());
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldAttributesSelect->render().'</td>'));
                // Field Null
                $fieldNullSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_NULL, 'field_null['.$id.']', $field->getVar('field_null'));
                $fieldNullSelect->addOptionArray($this->tdmcreate->getHandler('fieldnull')->getList());
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldNullSelect->render().'</td>'));
                // Field Default
                $fieldDefault = new XoopsFormText(_AM_TDMCREATE_FIELD_DEFAULT, 'field_default['.$id.']', 15, 25, $field->getVar('field_default'));
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldDefault->render().'</td>'));
                // Field Key
                $fieldKeySelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_KEY, 'field_key['.$id.']', $field->getVar('field_key'));
                $fieldKeySelect->addOptionArray($this->tdmcreate->getHandler('fieldkey')->getList());
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldKeySelect->render().'</td>'));
                // Field Void
                if ((1 == $id) && (1 == $tableAutoincrement)) {
                    $form->addElement(new TDMCreateFormLabel('<td>&nbsp;</td></tr>'));
                } else {
                    // Box header row
                    $parametersTray = new XoopsFormElementTray('', '<br />');
                    // Field Elements
                    $criteriaElement = new CriteriaCompo();
                    $criteriaElement->add(new Criteria('fieldelement_tid', 0));
                    $criteriaTable = new CriteriaCompo();
                    $criteriaTable->add(new Criteria('fieldelement_mid', $fieldMid));
                    $fieldElementsSelect = new XoopsFormSelect(_AM_TDMCREATE_FIELD_ELEMENT_NAME, 'field_element['.$id.']', $field->getVar('field_element'));
                    $fieldElementsSelect->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteriaElement));
                    $fieldElementsSelect->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteriaTable));
                    unset($criteriaElement, $criteriaTable);
                    $parametersTray->addElement($fieldElementsSelect);

                    $checkFieldParent = new XoopsFormCheckBox(' ', 'field_parent['.$id.']', $field->getVar('field_parent'));
                    $checkFieldParent->addOption(1, _AM_TDMCREATE_FIELD_PARENT);
                    $parametersTray->addElement($checkFieldParent);

                    $checkFieldAdmin = new XoopsFormCheckBox(' ', 'field_admin['.$id.']', $field->getVar('field_admin'));
                    $checkFieldAdmin->addOption(1, _AM_TDMCREATE_FIELD_ADMIN);
                    $parametersTray->addElement($checkFieldAdmin);

                    $checkFieldInList = new XoopsFormCheckBox(' ', 'field_inlist['.$id.']', $field->getVar('field_inlist'));
                    $checkFieldInList->addOption(1, _AM_TDMCREATE_FIELD_INLIST);
                    $parametersTray->addElement($checkFieldInList);

                    $checkFieldInForm = new XoopsFormCheckBox(' ', 'field_inform['.$id.']', $field->getVar('field_inform'));
                    $checkFieldInForm->addOption(1, _AM_TDMCREATE_FIELD_INFORM);
                    $parametersTray->addElement($checkFieldInForm);

                    $checkFieldUser = new XoopsFormCheckBox(' ', 'field_user['.$id.']', $field->getVar('field_user'));
                    $checkFieldUser->addOption(1, _AM_TDMCREATE_FIELD_USER);
                    $parametersTray->addElement($checkFieldUser);

                    $checkFieldThead = new XoopsFormCheckBox(' ', 'field_thead['.$id.']', $field->getVar('field_thead'));
                    $checkFieldThead->addOption(1, _AM_TDMCREATE_FIELD_THEAD);
                    $parametersTray->addElement($checkFieldThead);

                    $checkFieldTbody = new XoopsFormCheckBox(' ', 'field_tbody['.$id.']', $field->getVar('field_tbody'));
                    $checkFieldTbody->addOption(1, _AM_TDMCREATE_FIELD_TBODY);
                    $parametersTray->addElement($checkFieldTbody);

                    $checkFieldTfoot = new XoopsFormCheckBox(' ', 'field_tfoot['.$id.']', $field->getVar('field_tfoot'));
                    $checkFieldTfoot->addOption(1, _AM_TDMCREATE_FIELD_TFOOT);
                    $parametersTray->addElement($checkFieldTfoot);

                    $checkFieldBlock = new XoopsFormCheckBox('', 'field_block['.$id.']', $field->getVar('field_block'));
                    $checkFieldBlock->addOption(1, _AM_TDMCREATE_FIELD_BLOCK);
                    $parametersTray->addElement($checkFieldBlock);

                    $check_field_search = new XoopsFormCheckBox(' ', 'field_search['.$id.']', $field->getVar('field_search'));
                    $check_field_search->addOption(1, _AM_TDMCREATE_FIELD_SEARCH);
                    $parametersTray->addElement($check_field_search);

                    $checkFieldRequired = new XoopsFormCheckBox(' ', 'field_required['.$id.']', $field->getVar('field_required'));
                    $checkFieldRequired->addOption(1, _AM_TDMCREATE_FIELD_REQUIRED);
                    $parametersTray->addElement($checkFieldRequired);

                    $fieldMain = (1 == $field->getVar('field_main')) ? $id : 1;
                    $checkFieldMain = new TDMCreateFormRadio('', 'field_main', $fieldMain);
                    $checkFieldMain->addOption($id, _AM_TDMCREATE_FIELD_MAIN);
                    $parametersTray->addElement($checkFieldMain);
                    //
                    $form->addElement(new TDMCreateFormLabel('<td><div class="portlet"><div class="portlet-header">'._AM_TDMCREATE_FIELD_PARAMETERS_LIST.'</div><div class="portlet-content">'.$parametersTray->render().'</div></div></td></tr>'));
                }
            }
            ++$id;
        }
        // If you change number fields in tables,
        // adding missing fields or delete unnecessary fields
        // By goffy
        for ($i = $id; $i <= $fieldNumb; ++$i) {
            $class = ($class == 'even') ? 'odd' : 'even';
            $this->getFormNewLine($form, $class, $i, $fieldMid, $fieldTid, $fName, $tableAutoincrement);
        }
        unset($id);

        // Footer form
        return $fieldsForm->getFooterForm($form);
    }

    /*
    *  @private function getFooterForm
    *  @param null
    */
    /**
     * @param $form
     *
     * @return mixed
     */
    private function getFooterForm($form)
    {
        // Send Form Data
        $form->addElement(new TDMCreateFormLabel('</tbody>'));
        $form->addElement(new TDMCreateFormLabel('<tfoot><tr>'));
        $formHidden = new XoopsFormHidden('op', 'save');
        $formButton = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $form->addElement(new TDMCreateFormLabel('<td colspan="8">'.$formHidden->render().'</td>'));
        $form->addElement(new TDMCreateFormLabel('<td>'.$formButton->render().'</td>'));
        $form->addElement(new TDMCreateFormLabel('</tr></tfoot></table>'));

        return $form;
    }

    /**
     * Get Values.
     */
    public function getValuesFields($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        $ret['id'] = $this->getVar('field_id');
        $ret['mid'] = $this->getVar('field_mid');
        $ret['tid'] = $this->getVar('field_tid');
        $ret['order'] = $this->getVar('field_order');
        $ret['name'] = str_replace('_', ' ', ucfirst($this->getVar('field_name')));
        $ret['parent'] = $this->getVar('field_parent');
        $ret['inlist'] = $this->getVar('field_inlist');
        $ret['inform'] = $this->getVar('field_inform');
        $ret['admin'] = $this->getVar('field_admin');
        $ret['user'] = $this->getVar('field_user');
        $ret['block'] = $this->getVar('field_block');
        $ret['main'] = $this->getVar('field_main');
        $ret['search'] = $this->getVar('field_search');
        $ret['required'] = $this->getVar('field_required');

        return $ret;
    }

    /**
     * Get Options.
     */
    /**
     * @param $key
     *
     * @return string
     */
    public function getFieldsOptions()
    {
        $retField = array();
        foreach ($this->optionsFields as $optionField) {
            if ($this->getVar('field_'.$optionField) == 1) {
                array_push($retField, $optionField);
            }
        }

        return $retField;
    }

    /**
     * Get Defined Language.
     */
    /**
     * @param $lang
     *
     * @return string
     */
    private static function getDefinedLanguage($lang)
    {
        if (defined($lang)) {
            return constant($lang);
        }

        return $lang;
    }
}

/*
*  @Class TDMCreateFieldsHandler
*  @extends XoopsPersistableObjectHandler
*/

/**
 * Class TDMCreateFieldsHandler.
 */
class TDMCreateFieldsHandler extends XoopsPersistableObjectHandler
{
    /*
    *  @public function constructor class
    *  @param mixed $db
    */
    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'tdmcreate_fields', 'tdmcreatefields', 'field_id', 'field_name');
    }

    /**
     * @param bool $isNew
     *
     * @return object
     */
    public function &create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field.
     *
     * @param int  $i      field id
     * @param null $fields
     *
     * @return mixed reference to the <a href='psi_element://TDMCreateFields'>TDMCreateFields</a> object
     *               object
     */
    public function &get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id.
     *
     * @param null
     *
     * @return int reference to the {@link TDMCreateFields} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * get IDs of objects matching a condition.
     *
     * @param object $criteria {@link CriteriaElement} to match
     *
     * @return array of object IDs
     */
    public function &getIds($criteria)
    {
        return parent::getIds($criteria);
    }

    /**
     * insert a new field in the database.
     *
     * @param object $field reference to the {@link TDMCreateFields} object
     * @param bool   $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(&$field, $force = false)
    {
        if (!parent::insert($field, $force)) {
            return false;
        }

        return true;
    }

    /**
     * Get Count Fields.
     */
    public function getCountFields($start = 0, $limit = 0, $sort = 'field_id ASC, field_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria = $this->getCriteria($criteria, $start, $limit, $sort, $order);

        return $this->getCount($criteria);
    }

    /**
     * Get All Fields.
     */
    public function getAllFields($start = 0, $limit = 0, $sort = 'field_id ASC, field_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria = $this->getCriteria($criteria, $start, $limit, $sort, $order);

        return $this->getAll($criteria);
    }

    /**
     * Get All Fields By Module & Table Id.
     */
    public function getAllFieldsByModuleAndTableId($modId, $tabId, $start = 0, $limit = 0, $sort = 'field_order ASC, field_id, field_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('field_mid', $modId));
        $criteria->add(new Criteria('field_tid', $tabId));
        $criteria = $this->getCriteria($criteria, $start, $limit, $sort, $order);

        return $this->getAll($criteria);
    }

    /**
     * Get Criteria.
     */
    private function getCriteria($criteria, $start, $limit, $sort, $order)
    {
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort($sort);
        $criteria->setOrder($order);

        return $criteria;
    }
}
