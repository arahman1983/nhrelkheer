<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "studyinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$study_add = NULL; // Initialize page object first

class cstudy_add extends cstudy {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'study';

	// Page object name
	var $PageObjName = 'study_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (study)
		if (!isset($GLOBALS["study"]) || get_class($GLOBALS["study"]) == "cstudy") {
			$GLOBALS["study"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["study"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'study', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (admin_users)
		if (!isset($UserTable)) {
			$UserTable = new cadmin_users();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("studylist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $study;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($study);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("studylist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "studyview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->pic->Upload->Index = $objForm->Index;
		$this->pic->Upload->UploadFile();
		$this->pic->CurrentValue = $this->pic->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->breif->CurrentValue = NULL;
		$this->breif->OldValue = $this->breif->CurrentValue;
		$this->details->CurrentValue = NULL;
		$this->details->OldValue = $this->details->CurrentValue;
		$this->pic->Upload->DbValue = NULL;
		$this->pic->OldValue = $this->pic->Upload->DbValue;
		$this->pic->CurrentValue = NULL; // Clear file related field
		$this->key_wrds->CurrentValue = NULL;
		$this->key_wrds->OldValue = $this->key_wrds->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->breif->FldIsDetailKey) {
			$this->breif->setFormValue($objForm->GetValue("x_breif"));
		}
		if (!$this->details->FldIsDetailKey) {
			$this->details->setFormValue($objForm->GetValue("x_details"));
		}
		if (!$this->key_wrds->FldIsDetailKey) {
			$this->key_wrds->setFormValue($objForm->GetValue("x_key_wrds"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->title->CurrentValue = $this->title->FormValue;
		$this->breif->CurrentValue = $this->breif->FormValue;
		$this->details->CurrentValue = $this->details->FormValue;
		$this->key_wrds->CurrentValue = $this->key_wrds->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->title->setDbValue($rs->fields('title'));
		$this->breif->setDbValue($rs->fields('breif'));
		$this->details->setDbValue($rs->fields('details'));
		$this->pic->Upload->DbValue = $rs->fields('pic');
		$this->pic->CurrentValue = $this->pic->Upload->DbValue;
		$this->key_wrds->setDbValue($rs->fields('key_wrds'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->title->DbValue = $row['title'];
		$this->breif->DbValue = $row['breif'];
		$this->details->DbValue = $row['details'];
		$this->pic->Upload->DbValue = $row['pic'];
		$this->key_wrds->DbValue = $row['key_wrds'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// title
		// breif
		// details
		// pic
		// key_wrds

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// breif
		$this->breif->ViewValue = $this->breif->CurrentValue;
		$this->breif->ViewCustomAttributes = "";

		// details
		$this->details->ViewValue = $this->details->CurrentValue;
		$this->details->ViewCustomAttributes = "";

		// pic
		if (!ew_Empty($this->pic->Upload->DbValue)) {
			$this->pic->ImageAlt = $this->pic->FldAlt();
			$this->pic->ViewValue = $this->pic->Upload->DbValue;
		} else {
			$this->pic->ViewValue = "";
		}
		$this->pic->ViewCustomAttributes = "";

		// key_wrds
		$this->key_wrds->ViewValue = $this->key_wrds->CurrentValue;
		$this->key_wrds->ViewCustomAttributes = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// breif
			$this->breif->LinkCustomAttributes = "";
			$this->breif->HrefValue = "";
			$this->breif->TooltipValue = "";

			// details
			$this->details->LinkCustomAttributes = "";
			$this->details->HrefValue = "";
			$this->details->TooltipValue = "";

			// pic
			$this->pic->LinkCustomAttributes = "";
			if (!ew_Empty($this->pic->Upload->DbValue)) {
				$this->pic->HrefValue = ew_GetFileUploadUrl($this->pic, $this->pic->Upload->DbValue); // Add prefix/suffix
				$this->pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->pic->HrefValue = ew_ConvertFullUrl($this->pic->HrefValue);
			} else {
				$this->pic->HrefValue = "";
			}
			$this->pic->HrefValue2 = $this->pic->UploadPath . $this->pic->Upload->DbValue;
			$this->pic->TooltipValue = "";
			if ($this->pic->UseColorbox) {
				$this->pic->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->pic->LinkAttrs["data-rel"] = "study_x_pic";

				//$this->pic->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->pic->LinkAttrs["data-placement"] = "bottom";
				//$this->pic->LinkAttrs["data-container"] = "body";

				$this->pic->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// key_wrds
			$this->key_wrds->LinkCustomAttributes = "";
			$this->key_wrds->HrefValue = "";
			$this->key_wrds->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// breif
			$this->breif->EditAttrs["class"] = "form-control";
			$this->breif->EditCustomAttributes = "";
			$this->breif->EditValue = ew_HtmlEncode($this->breif->CurrentValue);
			$this->breif->PlaceHolder = ew_RemoveHtml($this->breif->FldCaption());

			// details
			$this->details->EditAttrs["class"] = "form-control";
			$this->details->EditCustomAttributes = "";
			$this->details->EditValue = ew_HtmlEncode($this->details->CurrentValue);
			$this->details->PlaceHolder = ew_RemoveHtml($this->details->FldCaption());

			// pic
			$this->pic->EditAttrs["class"] = "form-control";
			$this->pic->EditCustomAttributes = "";
			if (!ew_Empty($this->pic->Upload->DbValue)) {
				$this->pic->ImageAlt = $this->pic->FldAlt();
				$this->pic->EditValue = $this->pic->Upload->DbValue;
			} else {
				$this->pic->EditValue = "";
			}
			if (!ew_Empty($this->pic->CurrentValue))
				$this->pic->Upload->FileName = $this->pic->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->pic);

			// key_wrds
			$this->key_wrds->EditAttrs["class"] = "form-control";
			$this->key_wrds->EditCustomAttributes = "";
			$this->key_wrds->EditValue = ew_HtmlEncode($this->key_wrds->CurrentValue);
			$this->key_wrds->PlaceHolder = ew_RemoveHtml($this->key_wrds->FldCaption());

			// Edit refer script
			// title

			$this->title->HrefValue = "";

			// breif
			$this->breif->HrefValue = "";

			// details
			$this->details->HrefValue = "";

			// pic
			if (!ew_Empty($this->pic->Upload->DbValue)) {
				$this->pic->HrefValue = ew_GetFileUploadUrl($this->pic, $this->pic->Upload->DbValue); // Add prefix/suffix
				$this->pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->pic->HrefValue = ew_ConvertFullUrl($this->pic->HrefValue);
			} else {
				$this->pic->HrefValue = "";
			}
			$this->pic->HrefValue2 = $this->pic->UploadPath . $this->pic->Upload->DbValue;

			// key_wrds
			$this->key_wrds->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->breif->FldIsDetailKey && !is_null($this->breif->FormValue) && $this->breif->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->breif->FldCaption(), $this->breif->ReqErrMsg));
		}
		if (!$this->details->FldIsDetailKey && !is_null($this->details->FormValue) && $this->details->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->details->FldCaption(), $this->details->ReqErrMsg));
		}
		if ($this->pic->Upload->FileName == "" && !$this->pic->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pic->FldCaption(), $this->pic->ReqErrMsg));
		}
		if (!$this->key_wrds->FldIsDetailKey && !is_null($this->key_wrds->FormValue) && $this->key_wrds->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->key_wrds->FldCaption(), $this->key_wrds->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// breif
		$this->breif->SetDbValueDef($rsnew, $this->breif->CurrentValue, "", FALSE);

		// details
		$this->details->SetDbValueDef($rsnew, $this->details->CurrentValue, "", FALSE);

		// pic
		if (!$this->pic->Upload->KeepFile) {
			$this->pic->Upload->DbValue = ""; // No need to delete old file
			if ($this->pic->Upload->FileName == "") {
				$rsnew['pic'] = NULL;
			} else {
				$rsnew['pic'] = $this->pic->Upload->FileName;
			}
		}

		// key_wrds
		$this->key_wrds->SetDbValueDef($rsnew, $this->key_wrds->CurrentValue, "", FALSE);
		if (!$this->pic->Upload->KeepFile) {
			if (!ew_Empty($this->pic->Upload->Value)) {
				$rsnew['pic'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->pic->UploadPath), $rsnew['pic']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
				if (!$this->pic->Upload->KeepFile) {
					if (!ew_Empty($this->pic->Upload->Value)) {
						$this->pic->Upload->SaveToFile($this->pic->UploadPath, $rsnew['pic'], TRUE);
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// pic
		ew_CleanUploadTempPath($this->pic, $this->pic->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "studylist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($study_add)) $study_add = new cstudy_add();

// Page init
$study_add->Page_Init();

// Page main
$study_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$study_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fstudyadd = new ew_Form("fstudyadd", "add");

// Validate form
fstudyadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $study->title->FldCaption(), $study->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_breif");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $study->breif->FldCaption(), $study->breif->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_details");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $study->details->FldCaption(), $study->details->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_pic");
			elm = this.GetElements("fn_x" + infix + "_pic");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $study->pic->FldCaption(), $study->pic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_key_wrds");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $study->key_wrds->FldCaption(), $study->key_wrds->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fstudyadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fstudyadd.ValidateRequired = true;
<?php } else { ?>
fstudyadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $study_add->ShowPageHeader(); ?>
<?php
$study_add->ShowMessage();
?>
<form name="fstudyadd" id="fstudyadd" class="<?php echo $study_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($study_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $study_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="study">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($study->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_study_title" for="x_title" class="col-sm-2 control-label ewLabel"><?php echo $study->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $study->title->CellAttributes() ?>>
<span id="el_study_title">
<input type="text" data-table="study" data-field="x_title" name="x_title" id="x_title" size="70" maxlength="150" placeholder="<?php echo ew_HtmlEncode($study->title->getPlaceHolder()) ?>" value="<?php echo $study->title->EditValue ?>"<?php echo $study->title->EditAttributes() ?>>
</span>
<?php echo $study->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($study->breif->Visible) { // breif ?>
	<div id="r_breif" class="form-group">
		<label id="elh_study_breif" for="x_breif" class="col-sm-2 control-label ewLabel"><?php echo $study->breif->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $study->breif->CellAttributes() ?>>
<span id="el_study_breif">
<input type="text" data-table="study" data-field="x_breif" name="x_breif" id="x_breif" size="70" placeholder="<?php echo ew_HtmlEncode($study->breif->getPlaceHolder()) ?>" value="<?php echo $study->breif->EditValue ?>"<?php echo $study->breif->EditAttributes() ?>>
</span>
<?php echo $study->breif->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($study->details->Visible) { // details ?>
	<div id="r_details" class="form-group">
		<label id="elh_study_details" for="x_details" class="col-sm-2 control-label ewLabel"><?php echo $study->details->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $study->details->CellAttributes() ?>>
<span id="el_study_details">
<textarea data-table="study" data-field="x_details" name="x_details" id="x_details" cols="75" rows="4" placeholder="<?php echo ew_HtmlEncode($study->details->getPlaceHolder()) ?>"<?php echo $study->details->EditAttributes() ?>><?php echo $study->details->EditValue ?></textarea>
</span>
<?php echo $study->details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($study->pic->Visible) { // pic ?>
	<div id="r_pic" class="form-group">
		<label id="elh_study_pic" class="col-sm-2 control-label ewLabel"><?php echo $study->pic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $study->pic->CellAttributes() ?>>
<span id="el_study_pic">
<div id="fd_x_pic">
<span title="<?php echo $study->pic->FldTitle() ? $study->pic->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($study->pic->ReadOnly || $study->pic->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="study" data-field="x_pic" name="x_pic" id="x_pic"<?php echo $study->pic->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_pic" id= "fn_x_pic" value="<?php echo $study->pic->Upload->FileName ?>">
<input type="hidden" name="fa_x_pic" id= "fa_x_pic" value="0">
<input type="hidden" name="fs_x_pic" id= "fs_x_pic" value="200">
<input type="hidden" name="fx_x_pic" id= "fx_x_pic" value="<?php echo $study->pic->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_pic" id= "fm_x_pic" value="<?php echo $study->pic->UploadMaxFileSize ?>">
</div>
<table id="ft_x_pic" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $study->pic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($study->key_wrds->Visible) { // key_wrds ?>
	<div id="r_key_wrds" class="form-group">
		<label id="elh_study_key_wrds" for="x_key_wrds" class="col-sm-2 control-label ewLabel"><?php echo $study->key_wrds->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $study->key_wrds->CellAttributes() ?>>
<span id="el_study_key_wrds">
<input type="text" data-table="study" data-field="x_key_wrds" name="x_key_wrds" id="x_key_wrds" size="70" placeholder="<?php echo ew_HtmlEncode($study->key_wrds->getPlaceHolder()) ?>" value="<?php echo $study->key_wrds->EditValue ?>"<?php echo $study->key_wrds->EditAttributes() ?>>
</span>
<?php echo $study->key_wrds->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $study_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fstudyadd.Init();
</script>
<?php
$study_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$study_add->Page_Terminate();
?>
