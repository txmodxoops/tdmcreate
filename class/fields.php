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
 * tdmcreatereate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: 1.91 fields.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
require_once 'html/TDMCreateFormLabel.php';
require_once 'form/TDMCreateFormRadio.php';
require_once 'form/TDMCreateThemeForm.php';
/*
*  @Class TDMCreateFields
*  @extends XoopsObject 
*/
class TDMCreateFields extends XoopsObject
{ 
	/**
     * @var mixed
     */
    private $tdmcreate = null;	
	
	/*
	*  @public function constructor class
	*  @param null
	*/
	public function __construct()
	{
		$this->tdmcreate = TDMCreateHelper::getInstance();		
		$this->initVar('field_id', XOBJ_DTYPE_INT);
		$this->initVar('field_mid', XOBJ_DTYPE_INT);
		$this->initVar('field_tid', XOBJ_DTYPE_INT);
		$this->initVar('field_numb', XOBJ_DTYPE_INT);
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
		$this->initVar('field_inlist', XOBJ_DTYPE_INT);
		$this->initVar('field_inform', XOBJ_DTYPE_INT);
        $this->initVar('field_admin', XOBJ_DTYPE_INT);		
		$this->initVar('field_user', XOBJ_DTYPE_INT);	
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
	*  @static function &getInstance
	*  @param null
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
	*  @private function getHeaderForm
	*  @param mixed $action
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
		$form->addElement(new TDMCreateFormLabel('<table border="0" cellspacing="1" class="outer width100">'));
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
    *  @public function getFormNew 
    * 
    *  @param integer $field_mid 
    *  @param integer $field_tid 
    *  @param integer $field_numb 
    *  @param string $field_name 
    *  @param mixed $action 
    */ 
    public function getFormNew($field_mid = null, $field_tid = null, $field_numb = null, $field_name = null, $action = false)
    { 
        // Header function class 
        $fieldsForm = TDMCreateFields::getInstance(); 
        $form = $fieldsForm->getHeaderForm($action); 
        // Get handler tables
        $table_obj = $this->tdmcreate->getHandler('tables'); // Changed by goffy 
        $table_autoincrement = $table_obj->get($field_tid)->getVar('table_autoincrement'); // Added by goffy 
        // Loop for fields number
        $class = 'even'; 
        for($i = 1; $i <= $field_numb; $i++) { 
            $class = ($class == 'even') ? 'odd' : 'even'; 
            // Replaced creation of new line by new function - goffy 
            $this->getFormNewLine($form, $class, $i, $field_mid, $field_tid, $field_name, $table_autoincrement);
        }                         
        // Footer form 
        return $fieldsForm->getFooterForm($form); 
    } 
	
	/* 
    *  @private function getFormNewLine
    * 
    *  @param mixed $form 
    *  @param mixed $class 
    *  @param integer $i 
    *  @param integer $field_mid 
    *  @param integer $field_tid 
    *  @param mixed $f_name 
    *  @param integer $table_autoincrement 
	*  
	*  @author timgno - modified in getFormNewLine by goffy
    */ 
    private function getFormNewLine($form, $class, $i, $field_mid, $field_tid, $f_name, $table_autoincrement)
    { 
        $form->addElement(new XoopsFormHidden('field_id['.$i.']', 0));    
        $form->addElement(new XoopsFormHidden('field_mid', $field_mid)); 
        $form->addElement(new XoopsFormHidden('field_tid', $field_tid)); 
                         
        $form->addElement(new TDMCreateFormLabel('<tr class="'.$class.'">')); 
        // Index ID 
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$i.'</td>')); 
        // Field Name 
        $this_field_name = (!empty($f_name) ? ($i == 1 ? $f_name . '_id' : $f_name . '_') : '');
        $field_name = new XoopsFormText(_AM_TDMCREATE_FIELD_NAME, 'field_name['.$i.']', 15, 255, $this_field_name);            
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_name->render().'</td>')); 
        // Field Type     
        $value = ($i == 1) && ($table_autoincrement == 1) ? '2' : '';
		$fieldtype_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_type['.$i.']', $value);
        $fieldtype_select->addOptionArray($this->tdmcreate->getHandler('fieldtype')->getList());  
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldtype_select->render().'</td>')); 
        // Field Value     
        $value = ($i == 1) && ($table_autoincrement == 1) ? '8' : ''; 
        $field_value = new XoopsFormText(_AM_TDMCREATE_FIELD_VALUE, 'field_value['.$i.']', 5, 20, $value);
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_value->render().'</td>')); 
        // Field Attributes   
		$value = ($i == 1) && ($table_autoincrement == 1) ? '3' : '';
        $field_attributes_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_attribute['.$i.']', $value);  
        $field_attributes_select->addOptionArray($this->tdmcreate->getHandler('fieldattributes')->getList()); 
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_attributes_select->render().'</td>')); 
        // Field Null       
		$value = ($i == 1) && ($table_autoincrement == 1) ? '2' : '';
        $field_null_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_NULL, 'field_null['.$i.']', $value);
        $field_null_select->addOptionArray($this->tdmcreate->getHandler('fieldnull')->getList());             
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_null_select->render().'</td>')); 
        // Field Default 
        $field_default = new XoopsFormText(_AM_TDMCREATE_FIELD_DEFAULT, 'field_default['.$i.']', 15, 25);
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_default->render().'</td>')); 
        // Field Key 
		$value = ($i == 1) && ($table_autoincrement == 1) ? '2' : '';
        $field_key_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_KEY, 'field_key['.$i.']', $value);
        $field_key_select->addOptionArray($this->tdmcreate->getHandler('fieldkey')->getList()); 
        $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_key_select->render().'</td>')); 
        // Field Void             
        if( ($i == 1) && ($table_autoincrement == 1)) {                 
            $form->addElement(new TDMCreateFormLabel('<td>&nbsp;</td></tr>'));
        } else {             
            // Box header row                 
            $parameters_tray = new XoopsFormElementTray('', '<br />');                            
            // Field Elements     
            $criteria_element = new CriteriaCompo();                 
            $criteria_element->add(new Criteria('fieldelement_tid', 0));                     
            $criteria_table = new CriteriaCompo();     
            $criteria_table->add(new Criteria('fieldelement_mid', $field_mid));             
            $field_elements_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_ELEMENT_NAME, 'field_element['.$i.']');                
                $field_elements_select->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteria_element)); 
                $field_elements_select->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteria_table)); 
                unset($criteria_element); unset($criteria_table);                    
                $parameters_tray->addElement($field_elements_select); 
                             
            $field_parent = 0; 
                $check_field_parent = new XoopsFormCheckBox(' ', 'field_parent['.$i.']');
                $check_field_parent->addOption($field_parent, _AM_TDMCREATE_FIELD_PARENT ); 
                $parameters_tray->addElement($check_field_parent);
			/*$field_parent = ($table_autoincrement == 1) ? 2 : 1;
				$check_field_parent = new TDMCreateFormRadio('', 'field_parent', $field_parent);
				$check_field_parent->addOption($i, _AM_TDMCREATE_FIELD_PARENT );            
				$parameters_tray->addElement($check_field_parent);*/
                 
            $field_inlist = 0; 
                $check_field_inlist = new XoopsFormCheckBox(' ', 'field_inlist['.$i.']', $field_inlist);
                $check_field_inlist->addOption(1, _AM_TDMCREATE_FIELD_INLIST);
                $parameters_tray->addElement($check_field_inlist); 
             
            $field_inform = 0; 
                $check_field_inform = new XoopsFormCheckBox(' ', 'field_inform['.$i.']', $field_inform);
                $check_field_inform->addOption(1, _AM_TDMCREATE_FIELD_INFORM);
                $parameters_tray->addElement($check_field_inform); 

            $field_admin = 0; 
                $check_field_admin = new XoopsFormCheckBox(' ', 'field_admin['.$i.']', $field_admin);
                $check_field_admin->addOption(1, _AM_TDMCREATE_FIELD_ADMIN); 
                $parameters_tray->addElement($check_field_admin); 

            $field_user = 0; 
                $check_field_user = new XoopsFormCheckBox(' ', 'field_user['.$i.']', $field_user);
                $check_field_user->addOption(1, _AM_TDMCREATE_FIELD_USER); 
                $parameters_tray->addElement($check_field_user); 

            $field_block = 0; 
                $check_field_block = new XoopsFormCheckBox('', 'field_block['.$i.']', $field_block);
                $check_field_block->addOption(1, _AM_TDMCREATE_FIELD_BLOCK); 
                $parameters_tray->addElement($check_field_block);              
			
			$field_main = ($table_autoincrement == 1) ? 2 : 1;
				$check_field_main = new TDMCreateFormRadio('', 'field_main', $field_main);
				$check_field_main->addOption($i, _AM_TDMCREATE_FIELD_MAINFIELD );            
				$parameters_tray->addElement($check_field_main);
             
            $field_search = 0; 
                $check_field_search = new XoopsFormCheckBox(' ', 'field_search['.$i.']', $field_search);
                $check_field_search->addOption(1, _AM_TDMCREATE_FIELD_SEARCH);     
                $parameters_tray->addElement($check_field_search);                     

            $field_required = 0; 
                $check_field_required = new XoopsFormCheckBox(' ', 'field_required['.$i.']', $field_required);
                $check_field_required->addOption(1, _AM_TDMCREATE_FIELD_REQUIRED); 
                $parameters_tray->addElement($check_field_required); 
            $form->addElement(new TDMCreateFormLabel('<td><div class="portlet"><div class="portlet-header">'._AM_TDMCREATE_FIELD_PARAMETERS_LIST.'</div><div class="portlet-content">'.$parameters_tray->render().'</div></div></td></tr>')); 
        }     
    } 
     
    /* 
    *  @public function getFormEdit 
    * 
    *  @param integer $field_mid 
    *  @param integer $field_tid 
    *  @param mixed $action 
    */ 
    public function getFormEdit($field_mid = null, $field_tid = null, $field_numb = null, $action = false)
    {             
		// Header function class 
        $fields_form = TDMCreateFields::getInstance(); 
        $form = $fields_form->getHeaderForm($action); 
        // 
        $class = 'even';     
        // Get the number of fields - goffy 
        $tablesHandler =& $this->tdmcreate->getHandler('tables');
		$table_autoincrement = $tablesHandler->get($field_tid)->getVar('table_autoincrement');
        $table_nbfields = $tablesHandler->get($field_tid)->getVar('table_nbfields');
        $f_name = $tablesHandler->get($field_tid)->getVar('table_fieldname'); 

        // Get the list of fields 
        $criteria = new CriteriaCompo(); 
        $criteria->add(new Criteria('field_mid', $field_mid)); 
        $criteria->add(new Criteria('field_tid', $field_tid));		
        $criteria->setSort('field_id'); //added by goffy 
        $fields = $this->tdmcreate->getHandler('fields')->getObjects($criteria); 
        unset($criteria); 
        $id = 1; 
        foreach($fields as $field) {             
			$class = ($class == 'even') ? 'odd' : 'even'; 
            $field_id = intval($field->getVar('field_id')); 
            if ($id > $table_nbfields) {   // delete additional fields, if number of fields is reduced - goffy
                $fieldsObj =& $this->tdmcreate->getHandler('fields')->get( $field_id ); 
                $this->tdmcreate->getHandler('fields')->delete($fieldsObj, true); 
            } else {   
                // show field with settings 
                $form->addElement(new XoopsFormHidden('field_id['.$field_id.']', $field_id));     
                $form->addElement(new XoopsFormHidden('field_mid', $field_mid)); 
                $form->addElement(new XoopsFormHidden('field_tid', $field_tid)); 
				$form->addElement(new XoopsFormHidden('field_numb', $field_numb)); 
                                 
                $form->addElement(new TDMCreateFormLabel('<tr class="'.$class.'">')); 
                // Index ID 
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$id.'</td>'));     
                // Field Name 
                $field_name = new XoopsFormText(_AM_TDMCREATE_FIELD_NAME, 'field_name['.$id.']', 15, 255, $field->getVar('field_name'));             
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_name->render().'</td>')); 
                // Field Type             
                $fieldtype_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_type['.$id.']', $field->getVar('field_type')); 
                $fieldtype_select->addOptionArray($this->tdmcreate->getHandler('fieldtype')->getList());  
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$fieldtype_select->render().'</td>')); 
                // Field Value             
                $field_value = new XoopsFormText(_AM_TDMCREATE_FIELD_VALUE, 'field_value['.$id.']', 5, 20, $field->getVar('field_value')); 
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_value->render().'</td>')); 
                // Field Attributes                         
                $field_attributes_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_TYPE, 'field_attribute['.$id.']', $field->getVar('field_attribute'));   
                $field_attributes_select->addOptionArray($this->tdmcreate->getHandler('fieldattributes')->getList()); 
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_attributes_select->render().'</td>')); 
                // Field Null             
                $field_null_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_NULL, 'field_null['.$id.']', $field->getVar('field_null')); 
                $field_null_select->addOptionArray($this->tdmcreate->getHandler('fieldnull')->getList());             
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_null_select->render().'</td>')); 
                // Field Default 
                $field_default = new XoopsFormText(_AM_TDMCREATE_FIELD_DEFAULT, 'field_default['.$id.']', 15, 25, $field->getVar('field_default')); 
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_default->render().'</td>')); 
                // Field Key 
                $field_key_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_KEY, 'field_key['.$id.']', $field->getVar('field_key')); 
                $field_key_select->addOptionArray($this->tdmcreate->getHandler('fieldkey')->getList()); 
                $form->addElement(new TDMCreateFormLabel('<td class="center">'.$field_key_select->render().'</td>')); 
                // Field Void                 
                if( ($id == 1) && ($table_autoincrement == 1)) {                 
                    $form->addElement(new TDMCreateFormLabel('<td>&nbsp;</td></tr>')); 
                } else {             
                    // Box header row                 
                    $parameters_tray = new XoopsFormElementTray('', '<br />');                            
                    // Field Elements     
                    $criteria_element = new CriteriaCompo();                 
                    $criteria_element->add(new Criteria('fieldelement_tid', 0));                     
                    $criteria_table = new CriteriaCompo();     
                    $criteria_table->add(new Criteria('fieldelement_mid', $field_mid));             
                    $field_elements_select = new XoopsFormSelect(_AM_TDMCREATE_FIELD_ELEMENT_NAME, 'field_element['.$id.']', $field->getVar('field_element'));                 
                    $field_elements_select->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteria_element)); 
                    $field_elements_select->addOptionArray($this->tdmcreate->getHandler('fieldelements')->getList($criteria_table)); 
                    unset($criteria_element); unset($criteria_table);                    
                    $parameters_tray->addElement($field_elements_select); 
                                 
                    $check_field_parent = new XoopsFormCheckBox(' ', 'field_parent['.$id.']', $field->getVar('field_parent')); 
                    $check_field_parent->addOption(1, _AM_TDMCREATE_FIELD_PARENT ); 
                    $parameters_tray->addElement($check_field_parent);
					/*$field_parent = ($field->getVar('field_parent') == 1) ? $id : 1;
					$check_field_parent = new TDMCreateFormRadio('', 'field_parent', $field_parent);
					$check_field_parent->addOption($id, _AM_TDMCREATE_FIELD_PARENT );
					$parameters_tray->addElement($check_field_parent);*/
                     
                    $check_field_inlist = new XoopsFormCheckBox(' ', 'field_inlist['.$id.']', $field->getVar('field_inlist')); 
                    $check_field_inlist->addOption(1, _AM_TDMCREATE_FIELD_INLIST); 
                    $parameters_tray->addElement($check_field_inlist); 
                 
                    $check_field_inform = new XoopsFormCheckBox(' ', 'field_inform['.$id.']', $field->getVar('field_inform')); 
                    $check_field_inform->addOption(1, _AM_TDMCREATE_FIELD_INFORM); 
                    $parameters_tray->addElement($check_field_inform); 

                    $check_field_admin = new XoopsFormCheckBox(' ', 'field_admin['.$id.']', $field->getVar('field_admin')); 
                    $check_field_admin->addOption(1, _AM_TDMCREATE_FIELD_ADMIN); 
                    $parameters_tray->addElement($check_field_admin); 

                    $check_field_user = new XoopsFormCheckBox(' ', 'field_user['.$id.']', $field->getVar('field_user')); 
                    $check_field_user->addOption(1, _AM_TDMCREATE_FIELD_USER);
                    $parameters_tray->addElement($check_field_user); 

                    $check_field_block = new XoopsFormCheckBox('', 'field_block['.$id.']', $field->getVar('field_block')); 
                    $check_field_block->addOption(1, _AM_TDMCREATE_FIELD_BLOCK); 
                    $parameters_tray->addElement($check_field_block);                      
                      
					$field_main = ($field->getVar('field_main') == 1) ? $id : 1;
					$check_field_main = new TDMCreateFormRadio('', 'field_main', $field_main);
					$check_field_main->addOption($id, _AM_TDMCREATE_FIELD_MAINFIELD );
					$parameters_tray->addElement($check_field_main);
                     
                    $check_field_search = new XoopsFormCheckBox(' ', 'field_search['.$id.']', $field->getVar('field_search')); 
                    $check_field_search->addOption(1, _AM_TDMCREATE_FIELD_SEARCH); 
                    $parameters_tray->addElement($check_field_search);             

                    $check_field_required = new XoopsFormCheckBox(' ', 'field_required['.$id.']', $field->getVar('field_required')); 
                    $check_field_required->addOption(1, _AM_TDMCREATE_FIELD_REQUIRED); 
                    $parameters_tray->addElement($check_field_required); 
                    $form->addElement(new TDMCreateFormLabel('<td><div class="portlet"><div class="portlet-header">'._AM_TDMCREATE_FIELD_PARAMETERS_LIST.'</div><div class="portlet-content">'.$parameters_tray->render().'</div></div></td></tr>')); 
                }                
            } $id++;
        } 
        // If you change number fields in tables, 
		// adding missing fields or delete unnecessary fields 
        // By goffy 
        for ($i = $id; $i <= $table_nbfields; $i++) { 
            $class = ($class == 'even') ? 'odd' : 'even'; 
            $this->getFormNewLine($form, $class, $i, $field_mid, $field_tid, $f_name, $table_autoincrement); 
        } 
        unset($id);
        // Footer form 
        return $fields_form->getFooterForm($form); 
    } 
	
	/*
	*  @private function getFooterForm
	*  @param null
	*/
	private function getFooterForm($form)
	{
		// Send Form Data
		$form->addElement(new TDMCreateFormLabel('</tbody>'));
		$form->addElement(new TDMCreateFormLabel('<tfoot><tr>'));
		$form_hidden = new XoopsFormHidden('op', 'save');
		$form_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		$form->addElement(new TDMCreateFormLabel('<td colspan="8">'.$form_hidden->render().'</td>'));
		$form->addElement(new TDMCreateFormLabel('<td>'.$form_button->render().'</td>'));
		$form->addElement(new TDMCreateFormLabel('</tr></tfoot></table>'));
		return $form;
	}
}
/*
*  @Class TDMCreateFieldsHandler
*  @extends XoopsPersistableObjectHandler
*/
class TDMCreateFieldsHandler extends XoopsPersistableObjectHandler 
{	
	/*
	*  @public function constructor class
	*  @param mixed $db
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
     * retrieve a field
     *
     * @param int $i field id
     * @return mixed reference to the {@link TDMCreateFields} object
     */
    public function &get($i = null, $fields = null)
    {        
		return parent::get($i, $fields);
    }

	/**
     * get inserted id
     *
     * @param null
     * @return integer reference to the {@link TDMCreateFields} object
     */
	public function getInsertId() 
	{
		return $this->db->getInsertId();
	}

	/**
     * get IDs of objects matching a condition
     *
     * @param object $criteria {@link CriteriaElement} to match
     * @return array of object IDs
     */
    function &getIds($criteria)
    {
        return parent::getIds($criteria);
    }
		
	/**
     * insert a new field in the database
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
}