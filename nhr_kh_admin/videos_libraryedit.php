<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "videos_libraryinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$videos_library_edit = NULL; // Initialize page object first

class cvideos_library_edit extends cvideos_library {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'videos_library';

	// Page object name
	var $PageObjName = 'videos_library_edit';

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

		// Table object (videos_library)
		if (!isset($GLOBALS["videos_library"]) || get_class($GLOBALS["videos_library"]) == "cvideos_library") {
			$GLOBALS["videos_library"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["videos_library"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'videos_library', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("videos_librarylist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->vid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $videos_library;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($videos_library);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["vid"] <> "") {
			$this->vid->setQueryStringValue($_GET["vid"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->vid->CurrentValue == "")
			$this->Page_Terminate("videos_librarylist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("videos_librarylist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->vImage->Upload->Index = $objForm->Index;
		$this->vImage->Upload->UploadFile();
		$this->vImage->CurrentValue = $this->vImage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->vid->FldIsDetailKey)
			$this->vid->setFormValue($objForm->GetValue("x_vid"));
		if (!$this->vTitle->FldIsDetailKey) {
			$this->vTitle->setFormValue($objForm->GetValue("x_vTitle"));
		}
		if (!$this->vUrl->FldIsDetailKey) {
			$this->vUrl->setFormValue($objForm->GetValue("x_vUrl"));
		}
		if (!$this->onHome->FldIsDetailKey) {
			$this->onHome->setFormValue($objForm->GetValue("x_onHome"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->vid->CurrentValue = $this->vid->FormValue;
		$this->vTitle->CurrentValue = $this->vTitle->FormValue;
		$this->vUrl->CurrentValue = $this->vUrl->FormValue;
		$this->onHome->CurrentValue = $this->onHome->FormValue;
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
		$this->vid->setDbValue($rs->fields('vid'));
		$this->vTitle->setDbValue($rs->fields('vTitle'));
		$this->vImage->Upload->DbValue = $rs->fields('vImage');
		$this->vImage->CurrentValue = $this->vImage->Upload->DbValue;
		$this->vUrl->setDbValue($rs->fields('vUrl'));
		$this->onHome->setDbValue($rs->fields('onHome'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->vid->DbValue = $row['vid'];
		$this->vTitle->DbValue = $row['vTitle'];
		$this->vImage->Upload->DbValue = $row['vImage'];
		$this->vUrl->DbValue = $row['vUrl'];
		$this->onHome->DbValue = $row['onHome'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// vid
		// vTitle
		// vImage
		// vUrl
		// onHome

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// vid
		$this->vid->ViewValue = $this->vid->CurrentValue;
		$this->vid->ViewCustomAttributes = "";

		// vTitle
		$this->vTitle->ViewValue = $this->vTitle->CurrentValue;
		$this->vTitle->ViewCustomAttributes = "";

		// vImage
		if (!ew_Empty($this->vImage->Upload->DbValue)) {
			$this->vImage->ImageAlt = $this->vImage->FldAlt();
			$this->vImage->ViewValue = $this->vImage->Upload->DbValue;
		} else {
			$this->vImage->ViewValue = "";
		}
		$this->vImage->ViewCustomAttributes = "";

		// vUrl
		$this->vUrl->ViewValue = $this->vUrl->CurrentValue;
		$this->vUrl->ViewCustomAttributes = "";

		// onHome
		if (strval($this->onHome->CurrentValue) <> "") {
			$this->onHome->ViewValue = $this->onHome->OptionCaption($this->onHome->CurrentValue);
		} else {
			$this->onHome->ViewValue = NULL;
		}
		$this->onHome->ViewCustomAttributes = "";

			// vid
			$this->vid->LinkCustomAttributes = "";
			$this->vid->HrefValue = "";
			$this->vid->TooltipValue = "";

			// vTitle
			$this->vTitle->LinkCustomAttributes = "";
			$this->vTitle->HrefValue = "";
			$this->vTitle->TooltipValue = "";

			// vImage
			$this->vImage->LinkCustomAttributes = "";
			if (!ew_Empty($this->vImage->Upload->DbValue)) {
				$this->vImage->HrefValue = ew_GetFileUploadUrl($this->vImage, $this->vImage->Upload->DbValue); // Add prefix/suffix
				$this->vImage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->vImage->HrefValue = ew_ConvertFullUrl($this->vImage->HrefValue);
			} else {
				$this->vImage->HrefValue = "";
			}
			$this->vImage->HrefValue2 = $this->vImage->UploadPath . $this->vImage->Upload->DbValue;
			$this->vImage->TooltipValue = "";
			if ($this->vImage->UseColorbox) {
				$this->vImage->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->vImage->LinkAttrs["data-rel"] = "videos_library_x_vImage";

				//$this->vImage->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->vImage->LinkAttrs["data-placement"] = "bottom";
				//$this->vImage->LinkAttrs["data-container"] = "body";

				$this->vImage->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// vUrl
			$this->vUrl->LinkCustomAttributes = "";
			$this->vUrl->HrefValue = "";
			$this->vUrl->TooltipValue = "";

			// onHome
			$this->onHome->LinkCustomAttributes = "";
			$this->onHome->HrefValue = "";
			$this->onHome->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// vid
			$this->vid->EditAttrs["class"] = "form-control";
			$this->vid->EditCustomAttributes = "";
			$this->vid->EditValue = $this->vid->CurrentValue;
			$this->vid->ViewCustomAttributes = "";

			// vTitle
			$this->vTitle->EditAttrs["class"] = "form-control";
			$this->vTitle->EditCustomAttributes = "";
			$this->vTitle->EditValue = ew_HtmlEncode($this->vTitle->CurrentValue);
			$this->vTitle->PlaceHolder = ew_RemoveHtml($this->vTitle->FldCaption());

			// vImage
			$this->vImage->EditAttrs["class"] = "form-control";
			$this->vImage->EditCustomAttributes = "";
			if (!ew_Empty($this->vImage->Upload->DbValue)) {
				$this->vImage->ImageAlt = $this->vImage->FldAlt();
				$this->vImage->EditValue = $this->vImage->Upload->DbValue;
			} else {
				$this->vImage->EditValue = "";
			}
			if (!ew_Empty($this->vImage->CurrentValue))
				$this->vImage->Upload->FileName = $this->vImage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->vImage);

			// vUrl
			$this->vUrl->EditAttrs["class"] = "form-control";
			$this->vUrl->EditCustomAttributes = "";
			$this->vUrl->EditValue = ew_HtmlEncode($this->vUrl->CurrentValue);
			$this->vUrl->PlaceHolder = ew_RemoveHtml($this->vUrl->FldCaption());

			// onHome
			$this->onHome->EditCustomAttributes = "";
			$this->onHome->EditValue = $this->onHome->Options(FALSE);

			// Edit refer script
			// vid

			$this->vid->HrefValue = "";

			// vTitle
			$this->vTitle->HrefValue = "";

			// vImage
			if (!ew_Empty($this->vImage->Upload->DbValue)) {
				$this->vImage->HrefValue = ew_GetFileUploadUrl($this->vImage, $this->vImage->Upload->DbValue); // Add prefix/suffix
				$this->vImage->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->vImage->HrefValue = ew_ConvertFullUrl($this->vImage->HrefValue);
			} else {
				$this->vImage->HrefValue = "";
			}
			$this->vImage->HrefValue2 = $this->vImage->UploadPath . $this->vImage->Upload->DbValue;

			// vUrl
			$this->vUrl->HrefValue = "";

			// onHome
			$this->onHome->HrefValue = "";
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
		if (!$this->vTitle->FldIsDetailKey && !is_null($this->vTitle->FormValue) && $this->vTitle->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vTitle->FldCaption(), $this->vTitle->ReqErrMsg));
		}
		if ($this->vImage->Upload->FileName == "" && !$this->vImage->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vImage->FldCaption(), $this->vImage->ReqErrMsg));
		}
		if (!$this->vUrl->FldIsDetailKey && !is_null($this->vUrl->FormValue) && $this->vUrl->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vUrl->FldCaption(), $this->vUrl->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// vTitle
			$this->vTitle->SetDbValueDef($rsnew, $this->vTitle->CurrentValue, "", $this->vTitle->ReadOnly);

			// vImage
			if (!($this->vImage->ReadOnly) && !$this->vImage->Upload->KeepFile) {
				$this->vImage->Upload->DbValue = $rsold['vImage']; // Get original value
				if ($this->vImage->Upload->FileName == "") {
					$rsnew['vImage'] = NULL;
				} else {
					$rsnew['vImage'] = $this->vImage->Upload->FileName;
				}
			}

			// vUrl
			$this->vUrl->SetDbValueDef($rsnew, $this->vUrl->CurrentValue, "", $this->vUrl->ReadOnly);

			// onHome
			$this->onHome->SetDbValueDef($rsnew, $this->onHome->CurrentValue, NULL, $this->onHome->ReadOnly);
			if (!$this->vImage->Upload->KeepFile) {
				if (!ew_Empty($this->vImage->Upload->Value)) {
					$rsnew['vImage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->vImage->UploadPath), $rsnew['vImage']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if (!$this->vImage->Upload->KeepFile) {
						if (!ew_Empty($this->vImage->Upload->Value)) {
							$this->vImage->Upload->SaveToFile($this->vImage->UploadPath, $rsnew['vImage'], TRUE);
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
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// vImage
		ew_CleanUploadTempPath($this->vImage, $this->vImage->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "videos_librarylist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($videos_library_edit)) $videos_library_edit = new cvideos_library_edit();

// Page init
$videos_library_edit->Page_Init();

// Page main
$videos_library_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$videos_library_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fvideos_libraryedit = new ew_Form("fvideos_libraryedit", "edit");

// Validate form
fvideos_libraryedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_vTitle");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $videos_library->vTitle->FldCaption(), $videos_library->vTitle->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_vImage");
			elm = this.GetElements("fn_x" + infix + "_vImage");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $videos_library->vImage->FldCaption(), $videos_library->vImage->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_vUrl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $videos_library->vUrl->FldCaption(), $videos_library->vUrl->ReqErrMsg)) ?>");

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
fvideos_libraryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvideos_libraryedit.ValidateRequired = true;
<?php } else { ?>
fvideos_libraryedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvideos_libraryedit.Lists["x_onHome"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvideos_libraryedit.Lists["x_onHome"].Options = <?php echo json_encode($videos_library->onHome->Options()) ?>;

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
<?php $videos_library_edit->ShowPageHeader(); ?>
<?php
$videos_library_edit->ShowMessage();
?>
<form name="fvideos_libraryedit" id="fvideos_libraryedit" class="<?php echo $videos_library_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($videos_library_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $videos_library_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="videos_library">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($videos_library->vid->Visible) { // vid ?>
	<div id="r_vid" class="form-group">
		<label id="elh_videos_library_vid" class="col-sm-2 control-label ewLabel"><?php echo $videos_library->vid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $videos_library->vid->CellAttributes() ?>>
<span id="el_videos_library_vid">
<span<?php echo $videos_library->vid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $videos_library->vid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="videos_library" data-field="x_vid" name="x_vid" id="x_vid" value="<?php echo ew_HtmlEncode($videos_library->vid->CurrentValue) ?>">
<?php echo $videos_library->vid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($videos_library->vTitle->Visible) { // vTitle ?>
	<div id="r_vTitle" class="form-group">
		<label id="elh_videos_library_vTitle" for="x_vTitle" class="col-sm-2 control-label ewLabel"><?php echo $videos_library->vTitle->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $videos_library->vTitle->CellAttributes() ?>>
<span id="el_videos_library_vTitle">
<input type="text" data-table="videos_library" data-field="x_vTitle" name="x_vTitle" id="x_vTitle" size="70" maxlength="200" placeholder="<?php echo ew_HtmlEncode($videos_library->vTitle->getPlaceHolder()) ?>" value="<?php echo $videos_library->vTitle->EditValue ?>"<?php echo $videos_library->vTitle->EditAttributes() ?>>
</span>
<?php echo $videos_library->vTitle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($videos_library->vImage->Visible) { // vImage ?>
	<div id="r_vImage" class="form-group">
		<label id="elh_videos_library_vImage" class="col-sm-2 control-label ewLabel"><?php echo $videos_library->vImage->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $videos_library->vImage->CellAttributes() ?>>
<span id="el_videos_library_vImage">
<div id="fd_x_vImage">
<span title="<?php echo $videos_library->vImage->FldTitle() ? $videos_library->vImage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($videos_library->vImage->ReadOnly || $videos_library->vImage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="videos_library" data-field="x_vImage" name="x_vImage" id="x_vImage"<?php echo $videos_library->vImage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_vImage" id= "fn_x_vImage" value="<?php echo $videos_library->vImage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_vImage"] == "0") { ?>
<input type="hidden" name="fa_x_vImage" id= "fa_x_vImage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_vImage" id= "fa_x_vImage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_vImage" id= "fs_x_vImage" value="200">
<input type="hidden" name="fx_x_vImage" id= "fx_x_vImage" value="<?php echo $videos_library->vImage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_vImage" id= "fm_x_vImage" value="<?php echo $videos_library->vImage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_vImage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $videos_library->vImage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($videos_library->vUrl->Visible) { // vUrl ?>
	<div id="r_vUrl" class="form-group">
		<label id="elh_videos_library_vUrl" for="x_vUrl" class="col-sm-2 control-label ewLabel"><?php echo $videos_library->vUrl->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $videos_library->vUrl->CellAttributes() ?>>
<span id="el_videos_library_vUrl">
<input type="text" data-table="videos_library" data-field="x_vUrl" name="x_vUrl" id="x_vUrl" size="70" placeholder="<?php echo ew_HtmlEncode($videos_library->vUrl->getPlaceHolder()) ?>" value="<?php echo $videos_library->vUrl->EditValue ?>"<?php echo $videos_library->vUrl->EditAttributes() ?>>
</span>
<?php echo $videos_library->vUrl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($videos_library->onHome->Visible) { // onHome ?>
	<div id="r_onHome" class="form-group">
		<label id="elh_videos_library_onHome" class="col-sm-2 control-label ewLabel"><?php echo $videos_library->onHome->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $videos_library->onHome->CellAttributes() ?>>
<span id="el_videos_library_onHome">
<div id="tp_x_onHome" class="ewTemplate"><input type="radio" data-table="videos_library" data-field="x_onHome" data-value-separator="<?php echo ew_HtmlEncode(is_array($videos_library->onHome->DisplayValueSeparator) ? json_encode($videos_library->onHome->DisplayValueSeparator) : $videos_library->onHome->DisplayValueSeparator) ?>" name="x_onHome" id="x_onHome" value="{value}"<?php echo $videos_library->onHome->EditAttributes() ?>></div>
<div id="dsl_x_onHome" data-repeatcolumn="5" class="ewItemList"><div>
<?php
$arwrk = $videos_library->onHome->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($videos_library->onHome->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-table="videos_library" data-field="x_onHome" name="x_onHome" id="x_onHome_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $videos_library->onHome->EditAttributes() ?>><?php echo $videos_library->onHome->DisplayValue($arwrk[$rowcntwrk]) ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
	if ($emptywrk && strval($videos_library->onHome->CurrentValue) <> "") {
?>
<label class="radio-inline"><input type="radio" data-table="videos_library" data-field="x_onHome" name="x_onHome" id="x_onHome_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($videos_library->onHome->CurrentValue) ?>" checked<?php echo $videos_library->onHome->EditAttributes() ?>><?php echo $videos_library->onHome->CurrentValue ?></label>
<?php
    }
}
?>
</div></div>
</span>
<?php echo $videos_library->onHome->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $videos_library_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fvideos_libraryedit.Init();
</script>
<?php
$videos_library_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$videos_library_edit->Page_Terminate();
?>
