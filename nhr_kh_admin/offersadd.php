<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "offersinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$offers_add = NULL; // Initialize page object first

class coffers_add extends coffers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'offers';

	// Page object name
	var $PageObjName = 'offers_add';

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

		// Table object (offers)
		if (!isset($GLOBALS["offers"]) || get_class($GLOBALS["offers"]) == "coffers") {
			$GLOBALS["offers"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["offers"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'offers', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("offerslist.php"));
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
		global $EW_EXPORT, $offers;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($offers);
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
					$this->Page_Terminate("offerslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "offersview.php")
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
		$this->offers_pic->Upload->Index = $objForm->Index;
		$this->offers_pic->Upload->UploadFile();
		$this->offers_pic->CurrentValue = $this->offers_pic->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->offers_title->CurrentValue = NULL;
		$this->offers_title->OldValue = $this->offers_title->CurrentValue;
		$this->offers_brief->CurrentValue = NULL;
		$this->offers_brief->OldValue = $this->offers_brief->CurrentValue;
		$this->offers_details->CurrentValue = NULL;
		$this->offers_details->OldValue = $this->offers_details->CurrentValue;
		$this->offers_pic->Upload->DbValue = NULL;
		$this->offers_pic->OldValue = $this->offers_pic->Upload->DbValue;
		$this->offers_pic->CurrentValue = NULL; // Clear file related field
		$this->project->CurrentValue = NULL;
		$this->project->OldValue = $this->project->CurrentValue;
		$this->offers_keywrds->CurrentValue = NULL;
		$this->offers_keywrds->OldValue = $this->offers_keywrds->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->offers_title->FldIsDetailKey) {
			$this->offers_title->setFormValue($objForm->GetValue("x_offers_title"));
		}
		if (!$this->offers_brief->FldIsDetailKey) {
			$this->offers_brief->setFormValue($objForm->GetValue("x_offers_brief"));
		}
		if (!$this->offers_details->FldIsDetailKey) {
			$this->offers_details->setFormValue($objForm->GetValue("x_offers_details"));
		}
		if (!$this->project->FldIsDetailKey) {
			$this->project->setFormValue($objForm->GetValue("x_project"));
		}
		if (!$this->offers_keywrds->FldIsDetailKey) {
			$this->offers_keywrds->setFormValue($objForm->GetValue("x_offers_keywrds"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->offers_title->CurrentValue = $this->offers_title->FormValue;
		$this->offers_brief->CurrentValue = $this->offers_brief->FormValue;
		$this->offers_details->CurrentValue = $this->offers_details->FormValue;
		$this->project->CurrentValue = $this->project->FormValue;
		$this->offers_keywrds->CurrentValue = $this->offers_keywrds->FormValue;
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
		$this->offers_title->setDbValue($rs->fields('offers_title'));
		$this->offers_brief->setDbValue($rs->fields('offers_brief'));
		$this->offers_details->setDbValue($rs->fields('offers_details'));
		$this->offers_pic->Upload->DbValue = $rs->fields('offers_pic');
		$this->offers_pic->CurrentValue = $this->offers_pic->Upload->DbValue;
		$this->project->setDbValue($rs->fields('project'));
		$this->offers_keywrds->setDbValue($rs->fields('offers_keywrds'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->offers_title->DbValue = $row['offers_title'];
		$this->offers_brief->DbValue = $row['offers_brief'];
		$this->offers_details->DbValue = $row['offers_details'];
		$this->offers_pic->Upload->DbValue = $row['offers_pic'];
		$this->project->DbValue = $row['project'];
		$this->offers_keywrds->DbValue = $row['offers_keywrds'];
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
		// offers_title
		// offers_brief
		// offers_details
		// offers_pic
		// project
		// offers_keywrds

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// offers_title
		$this->offers_title->ViewValue = $this->offers_title->CurrentValue;
		$this->offers_title->ViewCustomAttributes = "";

		// offers_brief
		$this->offers_brief->ViewValue = $this->offers_brief->CurrentValue;
		$this->offers_brief->ViewCustomAttributes = "";

		// offers_details
		$this->offers_details->ViewValue = $this->offers_details->CurrentValue;
		$this->offers_details->ViewCustomAttributes = "";

		// offers_pic
		if (!ew_Empty($this->offers_pic->Upload->DbValue)) {
			$this->offers_pic->ImageAlt = $this->offers_pic->FldAlt();
			$this->offers_pic->ViewValue = $this->offers_pic->Upload->DbValue;
		} else {
			$this->offers_pic->ViewValue = "";
		}
		$this->offers_pic->ViewCustomAttributes = "";

		// project
		if (strval($this->project->CurrentValue) <> "") {
			$sFilterWrk = "`project_title`" . ew_SearchString("=", $this->project->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `project_title`, `project_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `projects`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->project, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->project->ViewValue = $this->project->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->project->ViewValue = $this->project->CurrentValue;
			}
		} else {
			$this->project->ViewValue = NULL;
		}
		$this->project->ViewCustomAttributes = "";

		// offers_keywrds
		$this->offers_keywrds->ViewValue = $this->offers_keywrds->CurrentValue;
		$this->offers_keywrds->ViewCustomAttributes = "";

			// offers_title
			$this->offers_title->LinkCustomAttributes = "";
			$this->offers_title->HrefValue = "";
			$this->offers_title->TooltipValue = "";

			// offers_brief
			$this->offers_brief->LinkCustomAttributes = "";
			$this->offers_brief->HrefValue = "";
			$this->offers_brief->TooltipValue = "";

			// offers_details
			$this->offers_details->LinkCustomAttributes = "";
			$this->offers_details->HrefValue = "";
			$this->offers_details->TooltipValue = "";

			// offers_pic
			$this->offers_pic->LinkCustomAttributes = "";
			if (!ew_Empty($this->offers_pic->Upload->DbValue)) {
				$this->offers_pic->HrefValue = ew_GetFileUploadUrl($this->offers_pic, $this->offers_pic->Upload->DbValue); // Add prefix/suffix
				$this->offers_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->offers_pic->HrefValue = ew_ConvertFullUrl($this->offers_pic->HrefValue);
			} else {
				$this->offers_pic->HrefValue = "";
			}
			$this->offers_pic->HrefValue2 = $this->offers_pic->UploadPath . $this->offers_pic->Upload->DbValue;
			$this->offers_pic->TooltipValue = "";
			if ($this->offers_pic->UseColorbox) {
				$this->offers_pic->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->offers_pic->LinkAttrs["data-rel"] = "offers_x_offers_pic";

				//$this->offers_pic->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->offers_pic->LinkAttrs["data-placement"] = "bottom";
				//$this->offers_pic->LinkAttrs["data-container"] = "body";

				$this->offers_pic->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// project
			$this->project->LinkCustomAttributes = "";
			$this->project->HrefValue = "";
			$this->project->TooltipValue = "";

			// offers_keywrds
			$this->offers_keywrds->LinkCustomAttributes = "";
			$this->offers_keywrds->HrefValue = "";
			$this->offers_keywrds->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// offers_title
			$this->offers_title->EditAttrs["class"] = "form-control";
			$this->offers_title->EditCustomAttributes = "";
			$this->offers_title->EditValue = ew_HtmlEncode($this->offers_title->CurrentValue);
			$this->offers_title->PlaceHolder = ew_RemoveHtml($this->offers_title->FldCaption());

			// offers_brief
			$this->offers_brief->EditAttrs["class"] = "form-control";
			$this->offers_brief->EditCustomAttributes = "";
			$this->offers_brief->EditValue = ew_HtmlEncode($this->offers_brief->CurrentValue);
			$this->offers_brief->PlaceHolder = ew_RemoveHtml($this->offers_brief->FldCaption());

			// offers_details
			$this->offers_details->EditAttrs["class"] = "form-control";
			$this->offers_details->EditCustomAttributes = "";
			$this->offers_details->EditValue = ew_HtmlEncode($this->offers_details->CurrentValue);
			$this->offers_details->PlaceHolder = ew_RemoveHtml($this->offers_details->FldCaption());

			// offers_pic
			$this->offers_pic->EditAttrs["class"] = "form-control";
			$this->offers_pic->EditCustomAttributes = "";
			if (!ew_Empty($this->offers_pic->Upload->DbValue)) {
				$this->offers_pic->ImageAlt = $this->offers_pic->FldAlt();
				$this->offers_pic->EditValue = $this->offers_pic->Upload->DbValue;
			} else {
				$this->offers_pic->EditValue = "";
			}
			if (!ew_Empty($this->offers_pic->CurrentValue))
				$this->offers_pic->Upload->FileName = $this->offers_pic->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->offers_pic);

			// project
			$this->project->EditAttrs["class"] = "form-control";
			$this->project->EditCustomAttributes = "";
			if (trim(strval($this->project->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`project_title`" . ew_SearchString("=", $this->project->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `project_title`, `project_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `projects`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->project, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->project->EditValue = $arwrk;

			// offers_keywrds
			$this->offers_keywrds->EditAttrs["class"] = "form-control";
			$this->offers_keywrds->EditCustomAttributes = "";
			$this->offers_keywrds->EditValue = ew_HtmlEncode($this->offers_keywrds->CurrentValue);
			$this->offers_keywrds->PlaceHolder = ew_RemoveHtml($this->offers_keywrds->FldCaption());

			// Edit refer script
			// offers_title

			$this->offers_title->HrefValue = "";

			// offers_brief
			$this->offers_brief->HrefValue = "";

			// offers_details
			$this->offers_details->HrefValue = "";

			// offers_pic
			if (!ew_Empty($this->offers_pic->Upload->DbValue)) {
				$this->offers_pic->HrefValue = ew_GetFileUploadUrl($this->offers_pic, $this->offers_pic->Upload->DbValue); // Add prefix/suffix
				$this->offers_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->offers_pic->HrefValue = ew_ConvertFullUrl($this->offers_pic->HrefValue);
			} else {
				$this->offers_pic->HrefValue = "";
			}
			$this->offers_pic->HrefValue2 = $this->offers_pic->UploadPath . $this->offers_pic->Upload->DbValue;

			// project
			$this->project->HrefValue = "";

			// offers_keywrds
			$this->offers_keywrds->HrefValue = "";
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
		if (!$this->offers_title->FldIsDetailKey && !is_null($this->offers_title->FormValue) && $this->offers_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->offers_title->FldCaption(), $this->offers_title->ReqErrMsg));
		}
		if (!$this->offers_brief->FldIsDetailKey && !is_null($this->offers_brief->FormValue) && $this->offers_brief->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->offers_brief->FldCaption(), $this->offers_brief->ReqErrMsg));
		}
		if (!$this->offers_details->FldIsDetailKey && !is_null($this->offers_details->FormValue) && $this->offers_details->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->offers_details->FldCaption(), $this->offers_details->ReqErrMsg));
		}
		if ($this->offers_pic->Upload->FileName == "" && !$this->offers_pic->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->offers_pic->FldCaption(), $this->offers_pic->ReqErrMsg));
		}
		if (!$this->project->FldIsDetailKey && !is_null($this->project->FormValue) && $this->project->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->project->FldCaption(), $this->project->ReqErrMsg));
		}
		if (!$this->offers_keywrds->FldIsDetailKey && !is_null($this->offers_keywrds->FormValue) && $this->offers_keywrds->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->offers_keywrds->FldCaption(), $this->offers_keywrds->ReqErrMsg));
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

		// offers_title
		$this->offers_title->SetDbValueDef($rsnew, $this->offers_title->CurrentValue, "", FALSE);

		// offers_brief
		$this->offers_brief->SetDbValueDef($rsnew, $this->offers_brief->CurrentValue, "", FALSE);

		// offers_details
		$this->offers_details->SetDbValueDef($rsnew, $this->offers_details->CurrentValue, "", FALSE);

		// offers_pic
		if (!$this->offers_pic->Upload->KeepFile) {
			$this->offers_pic->Upload->DbValue = ""; // No need to delete old file
			if ($this->offers_pic->Upload->FileName == "") {
				$rsnew['offers_pic'] = NULL;
			} else {
				$rsnew['offers_pic'] = $this->offers_pic->Upload->FileName;
			}
		}

		// project
		$this->project->SetDbValueDef($rsnew, $this->project->CurrentValue, "", FALSE);

		// offers_keywrds
		$this->offers_keywrds->SetDbValueDef($rsnew, $this->offers_keywrds->CurrentValue, "", FALSE);
		if (!$this->offers_pic->Upload->KeepFile) {
			if (!ew_Empty($this->offers_pic->Upload->Value)) {
				$rsnew['offers_pic'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->offers_pic->UploadPath), $rsnew['offers_pic']); // Get new file name
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
				if (!$this->offers_pic->Upload->KeepFile) {
					if (!ew_Empty($this->offers_pic->Upload->Value)) {
						$this->offers_pic->Upload->SaveToFile($this->offers_pic->UploadPath, $rsnew['offers_pic'], TRUE);
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

		// offers_pic
		ew_CleanUploadTempPath($this->offers_pic, $this->offers_pic->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "offerslist.php", "", $this->TableVar, TRUE);
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
if (!isset($offers_add)) $offers_add = new coffers_add();

// Page init
$offers_add->Page_Init();

// Page main
$offers_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$offers_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = foffersadd = new ew_Form("foffersadd", "add");

// Validate form
foffersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_offers_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $offers->offers_title->FldCaption(), $offers->offers_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_offers_brief");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $offers->offers_brief->FldCaption(), $offers->offers_brief->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_offers_details");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $offers->offers_details->FldCaption(), $offers->offers_details->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_offers_pic");
			elm = this.GetElements("fn_x" + infix + "_offers_pic");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $offers->offers_pic->FldCaption(), $offers->offers_pic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_project");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $offers->project->FldCaption(), $offers->project->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_offers_keywrds");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $offers->offers_keywrds->FldCaption(), $offers->offers_keywrds->ReqErrMsg)) ?>");

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
foffersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foffersadd.ValidateRequired = true;
<?php } else { ?>
foffersadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
foffersadd.Lists["x_project"] = {"LinkField":"x_project_title","Ajax":true,"AutoFill":false,"DisplayFields":["x_project_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $offers_add->ShowPageHeader(); ?>
<?php
$offers_add->ShowMessage();
?>
<form name="foffersadd" id="foffersadd" class="<?php echo $offers_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($offers_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $offers_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="offers">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($offers->offers_title->Visible) { // offers_title ?>
	<div id="r_offers_title" class="form-group">
		<label id="elh_offers_offers_title" for="x_offers_title" class="col-sm-2 control-label ewLabel"><?php echo $offers->offers_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $offers->offers_title->CellAttributes() ?>>
<span id="el_offers_offers_title">
<input type="text" data-table="offers" data-field="x_offers_title" name="x_offers_title" id="x_offers_title" size="70" maxlength="200" placeholder="<?php echo ew_HtmlEncode($offers->offers_title->getPlaceHolder()) ?>" value="<?php echo $offers->offers_title->EditValue ?>"<?php echo $offers->offers_title->EditAttributes() ?>>
</span>
<?php echo $offers->offers_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($offers->offers_brief->Visible) { // offers_brief ?>
	<div id="r_offers_brief" class="form-group">
		<label id="elh_offers_offers_brief" for="x_offers_brief" class="col-sm-2 control-label ewLabel"><?php echo $offers->offers_brief->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $offers->offers_brief->CellAttributes() ?>>
<span id="el_offers_offers_brief">
<input type="text" data-table="offers" data-field="x_offers_brief" name="x_offers_brief" id="x_offers_brief" size="70" placeholder="<?php echo ew_HtmlEncode($offers->offers_brief->getPlaceHolder()) ?>" value="<?php echo $offers->offers_brief->EditValue ?>"<?php echo $offers->offers_brief->EditAttributes() ?>>
</span>
<?php echo $offers->offers_brief->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($offers->offers_details->Visible) { // offers_details ?>
	<div id="r_offers_details" class="form-group">
		<label id="elh_offers_offers_details" for="x_offers_details" class="col-sm-2 control-label ewLabel"><?php echo $offers->offers_details->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $offers->offers_details->CellAttributes() ?>>
<span id="el_offers_offers_details">
<textarea data-table="offers" data-field="x_offers_details" name="x_offers_details" id="x_offers_details" cols="75" rows="4" placeholder="<?php echo ew_HtmlEncode($offers->offers_details->getPlaceHolder()) ?>"<?php echo $offers->offers_details->EditAttributes() ?>><?php echo $offers->offers_details->EditValue ?></textarea>
</span>
<?php echo $offers->offers_details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($offers->offers_pic->Visible) { // offers_pic ?>
	<div id="r_offers_pic" class="form-group">
		<label id="elh_offers_offers_pic" class="col-sm-2 control-label ewLabel"><?php echo $offers->offers_pic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $offers->offers_pic->CellAttributes() ?>>
<span id="el_offers_offers_pic">
<div id="fd_x_offers_pic">
<span title="<?php echo $offers->offers_pic->FldTitle() ? $offers->offers_pic->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($offers->offers_pic->ReadOnly || $offers->offers_pic->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="offers" data-field="x_offers_pic" name="x_offers_pic" id="x_offers_pic"<?php echo $offers->offers_pic->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_offers_pic" id= "fn_x_offers_pic" value="<?php echo $offers->offers_pic->Upload->FileName ?>">
<input type="hidden" name="fa_x_offers_pic" id= "fa_x_offers_pic" value="0">
<input type="hidden" name="fs_x_offers_pic" id= "fs_x_offers_pic" value="200">
<input type="hidden" name="fx_x_offers_pic" id= "fx_x_offers_pic" value="<?php echo $offers->offers_pic->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_offers_pic" id= "fm_x_offers_pic" value="<?php echo $offers->offers_pic->UploadMaxFileSize ?>">
</div>
<table id="ft_x_offers_pic" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $offers->offers_pic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($offers->project->Visible) { // project ?>
	<div id="r_project" class="form-group">
		<label id="elh_offers_project" for="x_project" class="col-sm-2 control-label ewLabel"><?php echo $offers->project->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $offers->project->CellAttributes() ?>>
<span id="el_offers_project">
<select data-table="offers" data-field="x_project" data-value-separator="<?php echo ew_HtmlEncode(is_array($offers->project->DisplayValueSeparator) ? json_encode($offers->project->DisplayValueSeparator) : $offers->project->DisplayValueSeparator) ?>" id="x_project" name="x_project" size=75<?php echo $offers->project->EditAttributes() ?>>
<?php
if (is_array($offers->project->EditValue)) {
	$arwrk = $offers->project->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($offers->project->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $offers->project->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($offers->project->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($offers->project->CurrentValue) ?>" selected><?php echo $offers->project->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
$sSqlWrk = "SELECT `project_title`, `project_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `projects`";
$sWhereWrk = "";
$offers->project->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$offers->project->LookupFilters += array("f0" => "`project_title` = {filter_value}", "t0" => "200", "fn0" => "");
$sSqlWrk = "";
$offers->Lookup_Selecting($offers->project, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $offers->project->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_project" id="s_x_project" value="<?php echo $offers->project->LookupFilterQuery() ?>">
</span>
<?php echo $offers->project->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($offers->offers_keywrds->Visible) { // offers_keywrds ?>
	<div id="r_offers_keywrds" class="form-group">
		<label id="elh_offers_offers_keywrds" for="x_offers_keywrds" class="col-sm-2 control-label ewLabel"><?php echo $offers->offers_keywrds->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $offers->offers_keywrds->CellAttributes() ?>>
<span id="el_offers_offers_keywrds">
<input type="text" data-table="offers" data-field="x_offers_keywrds" name="x_offers_keywrds" id="x_offers_keywrds" size="70" placeholder="<?php echo ew_HtmlEncode($offers->offers_keywrds->getPlaceHolder()) ?>" value="<?php echo $offers->offers_keywrds->EditValue ?>"<?php echo $offers->offers_keywrds->EditAttributes() ?>>
</span>
<?php echo $offers->offers_keywrds->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $offers_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
foffersadd.Init();
</script>
<?php
$offers_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$offers_add->Page_Terminate();
?>
