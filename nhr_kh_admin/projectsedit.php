<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "projectsinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$projects_edit = NULL; // Initialize page object first

class cprojects_edit extends cprojects {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'projects';

	// Page object name
	var $PageObjName = 'projects_edit';

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

		// Table object (projects)
		if (!isset($GLOBALS["projects"]) || get_class($GLOBALS["projects"]) == "cprojects") {
			$GLOBALS["projects"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projects"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'projects', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("projectslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $projects;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($projects);
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
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
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
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("projectslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("projectslist.php"); // No matching record, return to list
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
		$this->project_pic->Upload->Index = $objForm->Index;
		$this->project_pic->Upload->UploadFile();
		$this->project_pic->CurrentValue = $this->project_pic->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->project_title->FldIsDetailKey) {
			$this->project_title->setFormValue($objForm->GetValue("x_project_title"));
		}
		if (!$this->project_brief->FldIsDetailKey) {
			$this->project_brief->setFormValue($objForm->GetValue("x_project_brief"));
		}
		if (!$this->project_details->FldIsDetailKey) {
			$this->project_details->setFormValue($objForm->GetValue("x_project_details"));
		}
		if (!$this->project_keywrds->FldIsDetailKey) {
			$this->project_keywrds->setFormValue($objForm->GetValue("x_project_keywrds"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->project_title->CurrentValue = $this->project_title->FormValue;
		$this->project_brief->CurrentValue = $this->project_brief->FormValue;
		$this->project_details->CurrentValue = $this->project_details->FormValue;
		$this->project_keywrds->CurrentValue = $this->project_keywrds->FormValue;
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
		$this->project_title->setDbValue($rs->fields('project_title'));
		$this->project_brief->setDbValue($rs->fields('project_brief'));
		$this->project_details->setDbValue($rs->fields('project_details'));
		$this->project_pic->Upload->DbValue = $rs->fields('project_pic');
		$this->project_pic->CurrentValue = $this->project_pic->Upload->DbValue;
		$this->project_keywrds->setDbValue($rs->fields('project_keywrds'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->project_title->DbValue = $row['project_title'];
		$this->project_brief->DbValue = $row['project_brief'];
		$this->project_details->DbValue = $row['project_details'];
		$this->project_pic->Upload->DbValue = $row['project_pic'];
		$this->project_keywrds->DbValue = $row['project_keywrds'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// project_title
		// project_brief
		// project_details
		// project_pic
		// project_keywrds

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// project_title
		$this->project_title->ViewValue = $this->project_title->CurrentValue;
		$this->project_title->ViewCustomAttributes = "";

		// project_brief
		$this->project_brief->ViewValue = $this->project_brief->CurrentValue;
		$this->project_brief->ViewCustomAttributes = "";

		// project_details
		$this->project_details->ViewValue = $this->project_details->CurrentValue;
		$this->project_details->ViewCustomAttributes = "";

		// project_pic
		if (!ew_Empty($this->project_pic->Upload->DbValue)) {
			$this->project_pic->ImageAlt = $this->project_pic->FldAlt();
			$this->project_pic->ViewValue = $this->project_pic->Upload->DbValue;
		} else {
			$this->project_pic->ViewValue = "";
		}
		$this->project_pic->ViewCustomAttributes = "";

		// project_keywrds
		$this->project_keywrds->ViewValue = $this->project_keywrds->CurrentValue;
		$this->project_keywrds->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// project_title
			$this->project_title->LinkCustomAttributes = "";
			$this->project_title->HrefValue = "";
			$this->project_title->TooltipValue = "";

			// project_brief
			$this->project_brief->LinkCustomAttributes = "";
			$this->project_brief->HrefValue = "";
			$this->project_brief->TooltipValue = "";

			// project_details
			$this->project_details->LinkCustomAttributes = "";
			$this->project_details->HrefValue = "";
			$this->project_details->TooltipValue = "";

			// project_pic
			$this->project_pic->LinkCustomAttributes = "";
			if (!ew_Empty($this->project_pic->Upload->DbValue)) {
				$this->project_pic->HrefValue = ew_GetFileUploadUrl($this->project_pic, $this->project_pic->Upload->DbValue); // Add prefix/suffix
				$this->project_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->project_pic->HrefValue = ew_ConvertFullUrl($this->project_pic->HrefValue);
			} else {
				$this->project_pic->HrefValue = "";
			}
			$this->project_pic->HrefValue2 = $this->project_pic->UploadPath . $this->project_pic->Upload->DbValue;
			$this->project_pic->TooltipValue = "";
			if ($this->project_pic->UseColorbox) {
				$this->project_pic->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->project_pic->LinkAttrs["data-rel"] = "projects_x_project_pic";

				//$this->project_pic->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->project_pic->LinkAttrs["data-placement"] = "bottom";
				//$this->project_pic->LinkAttrs["data-container"] = "body";

				$this->project_pic->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// project_keywrds
			$this->project_keywrds->LinkCustomAttributes = "";
			$this->project_keywrds->HrefValue = "";
			$this->project_keywrds->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// project_title
			$this->project_title->EditAttrs["class"] = "form-control";
			$this->project_title->EditCustomAttributes = "";
			$this->project_title->EditValue = ew_HtmlEncode($this->project_title->CurrentValue);
			$this->project_title->PlaceHolder = ew_RemoveHtml($this->project_title->FldCaption());

			// project_brief
			$this->project_brief->EditAttrs["class"] = "form-control";
			$this->project_brief->EditCustomAttributes = "";
			$this->project_brief->EditValue = ew_HtmlEncode($this->project_brief->CurrentValue);
			$this->project_brief->PlaceHolder = ew_RemoveHtml($this->project_brief->FldCaption());

			// project_details
			$this->project_details->EditAttrs["class"] = "form-control";
			$this->project_details->EditCustomAttributes = "";
			$this->project_details->EditValue = ew_HtmlEncode($this->project_details->CurrentValue);
			$this->project_details->PlaceHolder = ew_RemoveHtml($this->project_details->FldCaption());

			// project_pic
			$this->project_pic->EditAttrs["class"] = "form-control";
			$this->project_pic->EditCustomAttributes = "";
			if (!ew_Empty($this->project_pic->Upload->DbValue)) {
				$this->project_pic->ImageAlt = $this->project_pic->FldAlt();
				$this->project_pic->EditValue = $this->project_pic->Upload->DbValue;
			} else {
				$this->project_pic->EditValue = "";
			}
			if (!ew_Empty($this->project_pic->CurrentValue))
				$this->project_pic->Upload->FileName = $this->project_pic->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->project_pic);

			// project_keywrds
			$this->project_keywrds->EditAttrs["class"] = "form-control";
			$this->project_keywrds->EditCustomAttributes = "";
			$this->project_keywrds->EditValue = ew_HtmlEncode($this->project_keywrds->CurrentValue);
			$this->project_keywrds->PlaceHolder = ew_RemoveHtml($this->project_keywrds->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// project_title
			$this->project_title->HrefValue = "";

			// project_brief
			$this->project_brief->HrefValue = "";

			// project_details
			$this->project_details->HrefValue = "";

			// project_pic
			if (!ew_Empty($this->project_pic->Upload->DbValue)) {
				$this->project_pic->HrefValue = ew_GetFileUploadUrl($this->project_pic, $this->project_pic->Upload->DbValue); // Add prefix/suffix
				$this->project_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->project_pic->HrefValue = ew_ConvertFullUrl($this->project_pic->HrefValue);
			} else {
				$this->project_pic->HrefValue = "";
			}
			$this->project_pic->HrefValue2 = $this->project_pic->UploadPath . $this->project_pic->Upload->DbValue;

			// project_keywrds
			$this->project_keywrds->HrefValue = "";
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
		if (!$this->project_title->FldIsDetailKey && !is_null($this->project_title->FormValue) && $this->project_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->project_title->FldCaption(), $this->project_title->ReqErrMsg));
		}
		if (!$this->project_brief->FldIsDetailKey && !is_null($this->project_brief->FormValue) && $this->project_brief->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->project_brief->FldCaption(), $this->project_brief->ReqErrMsg));
		}
		if (!$this->project_details->FldIsDetailKey && !is_null($this->project_details->FormValue) && $this->project_details->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->project_details->FldCaption(), $this->project_details->ReqErrMsg));
		}
		if ($this->project_pic->Upload->FileName == "" && !$this->project_pic->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->project_pic->FldCaption(), $this->project_pic->ReqErrMsg));
		}
		if (!$this->project_keywrds->FldIsDetailKey && !is_null($this->project_keywrds->FormValue) && $this->project_keywrds->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->project_keywrds->FldCaption(), $this->project_keywrds->ReqErrMsg));
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

			// project_title
			$this->project_title->SetDbValueDef($rsnew, $this->project_title->CurrentValue, "", $this->project_title->ReadOnly);

			// project_brief
			$this->project_brief->SetDbValueDef($rsnew, $this->project_brief->CurrentValue, "", $this->project_brief->ReadOnly);

			// project_details
			$this->project_details->SetDbValueDef($rsnew, $this->project_details->CurrentValue, "", $this->project_details->ReadOnly);

			// project_pic
			if (!($this->project_pic->ReadOnly) && !$this->project_pic->Upload->KeepFile) {
				$this->project_pic->Upload->DbValue = $rsold['project_pic']; // Get original value
				if ($this->project_pic->Upload->FileName == "") {
					$rsnew['project_pic'] = NULL;
				} else {
					$rsnew['project_pic'] = $this->project_pic->Upload->FileName;
				}
			}

			// project_keywrds
			$this->project_keywrds->SetDbValueDef($rsnew, $this->project_keywrds->CurrentValue, "", $this->project_keywrds->ReadOnly);
			if (!$this->project_pic->Upload->KeepFile) {
				if (!ew_Empty($this->project_pic->Upload->Value)) {
					$rsnew['project_pic'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->project_pic->UploadPath), $rsnew['project_pic']); // Get new file name
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
					if (!$this->project_pic->Upload->KeepFile) {
						if (!ew_Empty($this->project_pic->Upload->Value)) {
							$this->project_pic->Upload->SaveToFile($this->project_pic->UploadPath, $rsnew['project_pic'], TRUE);
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

		// project_pic
		ew_CleanUploadTempPath($this->project_pic, $this->project_pic->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "projectslist.php", "", $this->TableVar, TRUE);
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
if (!isset($projects_edit)) $projects_edit = new cprojects_edit();

// Page init
$projects_edit->Page_Init();

// Page main
$projects_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projects_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fprojectsedit = new ew_Form("fprojectsedit", "edit");

// Validate form
fprojectsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_project_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $projects->project_title->FldCaption(), $projects->project_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_project_brief");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $projects->project_brief->FldCaption(), $projects->project_brief->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_project_details");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $projects->project_details->FldCaption(), $projects->project_details->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_project_pic");
			elm = this.GetElements("fn_x" + infix + "_project_pic");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $projects->project_pic->FldCaption(), $projects->project_pic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_project_keywrds");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $projects->project_keywrds->FldCaption(), $projects->project_keywrds->ReqErrMsg)) ?>");

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
fprojectsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojectsedit.ValidateRequired = true;
<?php } else { ?>
fprojectsedit.ValidateRequired = false; 
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
<?php $projects_edit->ShowPageHeader(); ?>
<?php
$projects_edit->ShowMessage();
?>
<form name="fprojectsedit" id="fprojectsedit" class="<?php echo $projects_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($projects_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $projects_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="projects">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($projects->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_projects_id" class="col-sm-2 control-label ewLabel"><?php echo $projects->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $projects->id->CellAttributes() ?>>
<span id="el_projects_id">
<span<?php echo $projects->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $projects->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="projects" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($projects->id->CurrentValue) ?>">
<?php echo $projects->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($projects->project_title->Visible) { // project_title ?>
	<div id="r_project_title" class="form-group">
		<label id="elh_projects_project_title" for="x_project_title" class="col-sm-2 control-label ewLabel"><?php echo $projects->project_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $projects->project_title->CellAttributes() ?>>
<span id="el_projects_project_title">
<input type="text" data-table="projects" data-field="x_project_title" name="x_project_title" id="x_project_title" size="70" maxlength="200" placeholder="<?php echo ew_HtmlEncode($projects->project_title->getPlaceHolder()) ?>" value="<?php echo $projects->project_title->EditValue ?>"<?php echo $projects->project_title->EditAttributes() ?>>
</span>
<?php echo $projects->project_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($projects->project_brief->Visible) { // project_brief ?>
	<div id="r_project_brief" class="form-group">
		<label id="elh_projects_project_brief" for="x_project_brief" class="col-sm-2 control-label ewLabel"><?php echo $projects->project_brief->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $projects->project_brief->CellAttributes() ?>>
<span id="el_projects_project_brief">
<input type="text" data-table="projects" data-field="x_project_brief" name="x_project_brief" id="x_project_brief" size="70" placeholder="<?php echo ew_HtmlEncode($projects->project_brief->getPlaceHolder()) ?>" value="<?php echo $projects->project_brief->EditValue ?>"<?php echo $projects->project_brief->EditAttributes() ?>>
</span>
<?php echo $projects->project_brief->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($projects->project_details->Visible) { // project_details ?>
	<div id="r_project_details" class="form-group">
		<label id="elh_projects_project_details" for="x_project_details" class="col-sm-2 control-label ewLabel"><?php echo $projects->project_details->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $projects->project_details->CellAttributes() ?>>
<span id="el_projects_project_details">
<textarea data-table="projects" data-field="x_project_details" name="x_project_details" id="x_project_details" cols="75" rows="4" placeholder="<?php echo ew_HtmlEncode($projects->project_details->getPlaceHolder()) ?>"<?php echo $projects->project_details->EditAttributes() ?>><?php echo $projects->project_details->EditValue ?></textarea>
</span>
<?php echo $projects->project_details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($projects->project_pic->Visible) { // project_pic ?>
	<div id="r_project_pic" class="form-group">
		<label id="elh_projects_project_pic" class="col-sm-2 control-label ewLabel"><?php echo $projects->project_pic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $projects->project_pic->CellAttributes() ?>>
<span id="el_projects_project_pic">
<div id="fd_x_project_pic">
<span title="<?php echo $projects->project_pic->FldTitle() ? $projects->project_pic->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($projects->project_pic->ReadOnly || $projects->project_pic->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="projects" data-field="x_project_pic" name="x_project_pic" id="x_project_pic"<?php echo $projects->project_pic->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_project_pic" id= "fn_x_project_pic" value="<?php echo $projects->project_pic->Upload->FileName ?>">
<?php if (@$_POST["fa_x_project_pic"] == "0") { ?>
<input type="hidden" name="fa_x_project_pic" id= "fa_x_project_pic" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_project_pic" id= "fa_x_project_pic" value="1">
<?php } ?>
<input type="hidden" name="fs_x_project_pic" id= "fs_x_project_pic" value="200">
<input type="hidden" name="fx_x_project_pic" id= "fx_x_project_pic" value="<?php echo $projects->project_pic->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_project_pic" id= "fm_x_project_pic" value="<?php echo $projects->project_pic->UploadMaxFileSize ?>">
</div>
<table id="ft_x_project_pic" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $projects->project_pic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($projects->project_keywrds->Visible) { // project_keywrds ?>
	<div id="r_project_keywrds" class="form-group">
		<label id="elh_projects_project_keywrds" for="x_project_keywrds" class="col-sm-2 control-label ewLabel"><?php echo $projects->project_keywrds->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $projects->project_keywrds->CellAttributes() ?>>
<span id="el_projects_project_keywrds">
<input type="text" data-table="projects" data-field="x_project_keywrds" name="x_project_keywrds" id="x_project_keywrds" size="70" placeholder="<?php echo ew_HtmlEncode($projects->project_keywrds->getPlaceHolder()) ?>" value="<?php echo $projects->project_keywrds->EditValue ?>"<?php echo $projects->project_keywrds->EditAttributes() ?>>
</span>
<?php echo $projects->project_keywrds->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $projects_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fprojectsedit.Init();
</script>
<?php
$projects_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$projects_edit->Page_Terminate();
?>
