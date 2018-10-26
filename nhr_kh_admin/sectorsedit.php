<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sectorsinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sectors_edit = NULL; // Initialize page object first

class csectors_edit extends csectors {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'sectors';

	// Page object name
	var $PageObjName = 'sectors_edit';

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

		// Table object (sectors)
		if (!isset($GLOBALS["sectors"]) || get_class($GLOBALS["sectors"]) == "csectors") {
			$GLOBALS["sectors"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sectors"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sectors', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("sectorslist.php"));
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
		global $EW_EXPORT, $sectors;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sectors);
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
			$this->Page_Terminate("sectorslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("sectorslist.php"); // No matching record, return to list
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
		$this->sector_icon->Upload->Index = $objForm->Index;
		$this->sector_icon->Upload->UploadFile();
		$this->sector_icon->CurrentValue = $this->sector_icon->Upload->FileName;
		$this->sector_pic->Upload->Index = $objForm->Index;
		$this->sector_pic->Upload->UploadFile();
		$this->sector_pic->CurrentValue = $this->sector_pic->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->sector_title->FldIsDetailKey) {
			$this->sector_title->setFormValue($objForm->GetValue("x_sector_title"));
		}
		if (!$this->sector_brief->FldIsDetailKey) {
			$this->sector_brief->setFormValue($objForm->GetValue("x_sector_brief"));
		}
		if (!$this->sector_details->FldIsDetailKey) {
			$this->sector_details->setFormValue($objForm->GetValue("x_sector_details"));
		}
		if (!$this->sector_keywrds->FldIsDetailKey) {
			$this->sector_keywrds->setFormValue($objForm->GetValue("x_sector_keywrds"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->sector_title->CurrentValue = $this->sector_title->FormValue;
		$this->sector_brief->CurrentValue = $this->sector_brief->FormValue;
		$this->sector_details->CurrentValue = $this->sector_details->FormValue;
		$this->sector_keywrds->CurrentValue = $this->sector_keywrds->FormValue;
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
		$this->sector_title->setDbValue($rs->fields('sector_title'));
		$this->sector_brief->setDbValue($rs->fields('sector_brief'));
		$this->sector_details->setDbValue($rs->fields('sector_details'));
		$this->sector_icon->Upload->DbValue = $rs->fields('sector_icon');
		$this->sector_icon->CurrentValue = $this->sector_icon->Upload->DbValue;
		$this->sector_pic->Upload->DbValue = $rs->fields('sector_pic');
		$this->sector_pic->CurrentValue = $this->sector_pic->Upload->DbValue;
		$this->sector_keywrds->setDbValue($rs->fields('sector_keywrds'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->sector_title->DbValue = $row['sector_title'];
		$this->sector_brief->DbValue = $row['sector_brief'];
		$this->sector_details->DbValue = $row['sector_details'];
		$this->sector_icon->Upload->DbValue = $row['sector_icon'];
		$this->sector_pic->Upload->DbValue = $row['sector_pic'];
		$this->sector_keywrds->DbValue = $row['sector_keywrds'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// sector_title
		// sector_brief
		// sector_details
		// sector_icon
		// sector_pic
		// sector_keywrds

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// sector_title
		$this->sector_title->ViewValue = $this->sector_title->CurrentValue;
		$this->sector_title->ViewCustomAttributes = "";

		// sector_brief
		$this->sector_brief->ViewValue = $this->sector_brief->CurrentValue;
		$this->sector_brief->ViewCustomAttributes = "";

		// sector_details
		$this->sector_details->ViewValue = $this->sector_details->CurrentValue;
		$this->sector_details->ViewCustomAttributes = "";

		// sector_icon
		if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
			$this->sector_icon->ImageAlt = $this->sector_icon->FldAlt();
			$this->sector_icon->ViewValue = $this->sector_icon->Upload->DbValue;
		} else {
			$this->sector_icon->ViewValue = "";
		}
		$this->sector_icon->ViewCustomAttributes = "";

		// sector_pic
		if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
			$this->sector_pic->ImageAlt = $this->sector_pic->FldAlt();
			$this->sector_pic->ViewValue = $this->sector_pic->Upload->DbValue;
		} else {
			$this->sector_pic->ViewValue = "";
		}
		$this->sector_pic->ViewCustomAttributes = "";

		// sector_keywrds
		$this->sector_keywrds->ViewValue = $this->sector_keywrds->CurrentValue;
		$this->sector_keywrds->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// sector_title
			$this->sector_title->LinkCustomAttributes = "";
			$this->sector_title->HrefValue = "";
			$this->sector_title->TooltipValue = "";

			// sector_brief
			$this->sector_brief->LinkCustomAttributes = "";
			$this->sector_brief->HrefValue = "";
			$this->sector_brief->TooltipValue = "";

			// sector_details
			$this->sector_details->LinkCustomAttributes = "";
			$this->sector_details->HrefValue = "";
			$this->sector_details->TooltipValue = "";

			// sector_icon
			$this->sector_icon->LinkCustomAttributes = "";
			if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
				$this->sector_icon->HrefValue = ew_GetFileUploadUrl($this->sector_icon, $this->sector_icon->Upload->DbValue); // Add prefix/suffix
				$this->sector_icon->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->sector_icon->HrefValue = ew_ConvertFullUrl($this->sector_icon->HrefValue);
			} else {
				$this->sector_icon->HrefValue = "";
			}
			$this->sector_icon->HrefValue2 = $this->sector_icon->UploadPath . $this->sector_icon->Upload->DbValue;
			$this->sector_icon->TooltipValue = "";
			if ($this->sector_icon->UseColorbox) {
				$this->sector_icon->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->sector_icon->LinkAttrs["data-rel"] = "sectors_x_sector_icon";

				//$this->sector_icon->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->sector_icon->LinkAttrs["data-placement"] = "bottom";
				//$this->sector_icon->LinkAttrs["data-container"] = "body";

				$this->sector_icon->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// sector_pic
			$this->sector_pic->LinkCustomAttributes = "";
			if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
				$this->sector_pic->HrefValue = ew_GetFileUploadUrl($this->sector_pic, $this->sector_pic->Upload->DbValue); // Add prefix/suffix
				$this->sector_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->sector_pic->HrefValue = ew_ConvertFullUrl($this->sector_pic->HrefValue);
			} else {
				$this->sector_pic->HrefValue = "";
			}
			$this->sector_pic->HrefValue2 = $this->sector_pic->UploadPath . $this->sector_pic->Upload->DbValue;
			$this->sector_pic->TooltipValue = "";
			if ($this->sector_pic->UseColorbox) {
				$this->sector_pic->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->sector_pic->LinkAttrs["data-rel"] = "sectors_x_sector_pic";

				//$this->sector_pic->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->sector_pic->LinkAttrs["data-placement"] = "bottom";
				//$this->sector_pic->LinkAttrs["data-container"] = "body";

				$this->sector_pic->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}

			// sector_keywrds
			$this->sector_keywrds->LinkCustomAttributes = "";
			$this->sector_keywrds->HrefValue = "";
			$this->sector_keywrds->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// sector_title
			$this->sector_title->EditAttrs["class"] = "form-control";
			$this->sector_title->EditCustomAttributes = "";
			$this->sector_title->EditValue = ew_HtmlEncode($this->sector_title->CurrentValue);
			$this->sector_title->PlaceHolder = ew_RemoveHtml($this->sector_title->FldCaption());

			// sector_brief
			$this->sector_brief->EditAttrs["class"] = "form-control";
			$this->sector_brief->EditCustomAttributes = "";
			$this->sector_brief->EditValue = ew_HtmlEncode($this->sector_brief->CurrentValue);
			$this->sector_brief->PlaceHolder = ew_RemoveHtml($this->sector_brief->FldCaption());

			// sector_details
			$this->sector_details->EditAttrs["class"] = "form-control";
			$this->sector_details->EditCustomAttributes = "";
			$this->sector_details->EditValue = ew_HtmlEncode($this->sector_details->CurrentValue);
			$this->sector_details->PlaceHolder = ew_RemoveHtml($this->sector_details->FldCaption());

			// sector_icon
			$this->sector_icon->EditAttrs["class"] = "form-control";
			$this->sector_icon->EditCustomAttributes = "";
			if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
				$this->sector_icon->ImageAlt = $this->sector_icon->FldAlt();
				$this->sector_icon->EditValue = $this->sector_icon->Upload->DbValue;
			} else {
				$this->sector_icon->EditValue = "";
			}
			if (!ew_Empty($this->sector_icon->CurrentValue))
				$this->sector_icon->Upload->FileName = $this->sector_icon->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->sector_icon);

			// sector_pic
			$this->sector_pic->EditAttrs["class"] = "form-control";
			$this->sector_pic->EditCustomAttributes = "";
			if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
				$this->sector_pic->ImageAlt = $this->sector_pic->FldAlt();
				$this->sector_pic->EditValue = $this->sector_pic->Upload->DbValue;
			} else {
				$this->sector_pic->EditValue = "";
			}
			if (!ew_Empty($this->sector_pic->CurrentValue))
				$this->sector_pic->Upload->FileName = $this->sector_pic->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->sector_pic);

			// sector_keywrds
			$this->sector_keywrds->EditAttrs["class"] = "form-control";
			$this->sector_keywrds->EditCustomAttributes = "";
			$this->sector_keywrds->EditValue = ew_HtmlEncode($this->sector_keywrds->CurrentValue);
			$this->sector_keywrds->PlaceHolder = ew_RemoveHtml($this->sector_keywrds->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// sector_title
			$this->sector_title->HrefValue = "";

			// sector_brief
			$this->sector_brief->HrefValue = "";

			// sector_details
			$this->sector_details->HrefValue = "";

			// sector_icon
			if (!ew_Empty($this->sector_icon->Upload->DbValue)) {
				$this->sector_icon->HrefValue = ew_GetFileUploadUrl($this->sector_icon, $this->sector_icon->Upload->DbValue); // Add prefix/suffix
				$this->sector_icon->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->sector_icon->HrefValue = ew_ConvertFullUrl($this->sector_icon->HrefValue);
			} else {
				$this->sector_icon->HrefValue = "";
			}
			$this->sector_icon->HrefValue2 = $this->sector_icon->UploadPath . $this->sector_icon->Upload->DbValue;

			// sector_pic
			if (!ew_Empty($this->sector_pic->Upload->DbValue)) {
				$this->sector_pic->HrefValue = ew_GetFileUploadUrl($this->sector_pic, $this->sector_pic->Upload->DbValue); // Add prefix/suffix
				$this->sector_pic->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->sector_pic->HrefValue = ew_ConvertFullUrl($this->sector_pic->HrefValue);
			} else {
				$this->sector_pic->HrefValue = "";
			}
			$this->sector_pic->HrefValue2 = $this->sector_pic->UploadPath . $this->sector_pic->Upload->DbValue;

			// sector_keywrds
			$this->sector_keywrds->HrefValue = "";
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
		if (!$this->sector_title->FldIsDetailKey && !is_null($this->sector_title->FormValue) && $this->sector_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sector_title->FldCaption(), $this->sector_title->ReqErrMsg));
		}
		if (!$this->sector_brief->FldIsDetailKey && !is_null($this->sector_brief->FormValue) && $this->sector_brief->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sector_brief->FldCaption(), $this->sector_brief->ReqErrMsg));
		}
		if (!$this->sector_details->FldIsDetailKey && !is_null($this->sector_details->FormValue) && $this->sector_details->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sector_details->FldCaption(), $this->sector_details->ReqErrMsg));
		}
		if ($this->sector_icon->Upload->FileName == "" && !$this->sector_icon->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sector_icon->FldCaption(), $this->sector_icon->ReqErrMsg));
		}
		if ($this->sector_pic->Upload->FileName == "" && !$this->sector_pic->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sector_pic->FldCaption(), $this->sector_pic->ReqErrMsg));
		}
		if (!$this->sector_keywrds->FldIsDetailKey && !is_null($this->sector_keywrds->FormValue) && $this->sector_keywrds->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sector_keywrds->FldCaption(), $this->sector_keywrds->ReqErrMsg));
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

			// sector_title
			$this->sector_title->SetDbValueDef($rsnew, $this->sector_title->CurrentValue, "", $this->sector_title->ReadOnly);

			// sector_brief
			$this->sector_brief->SetDbValueDef($rsnew, $this->sector_brief->CurrentValue, "", $this->sector_brief->ReadOnly);

			// sector_details
			$this->sector_details->SetDbValueDef($rsnew, $this->sector_details->CurrentValue, "", $this->sector_details->ReadOnly);

			// sector_icon
			if (!($this->sector_icon->ReadOnly) && !$this->sector_icon->Upload->KeepFile) {
				$this->sector_icon->Upload->DbValue = $rsold['sector_icon']; // Get original value
				if ($this->sector_icon->Upload->FileName == "") {
					$rsnew['sector_icon'] = NULL;
				} else {
					$rsnew['sector_icon'] = $this->sector_icon->Upload->FileName;
				}
			}

			// sector_pic
			if (!($this->sector_pic->ReadOnly) && !$this->sector_pic->Upload->KeepFile) {
				$this->sector_pic->Upload->DbValue = $rsold['sector_pic']; // Get original value
				if ($this->sector_pic->Upload->FileName == "") {
					$rsnew['sector_pic'] = NULL;
				} else {
					$rsnew['sector_pic'] = $this->sector_pic->Upload->FileName;
				}
			}

			// sector_keywrds
			$this->sector_keywrds->SetDbValueDef($rsnew, $this->sector_keywrds->CurrentValue, "", $this->sector_keywrds->ReadOnly);
			if (!$this->sector_icon->Upload->KeepFile) {
				if (!ew_Empty($this->sector_icon->Upload->Value)) {
					$rsnew['sector_icon'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->sector_icon->UploadPath), $rsnew['sector_icon']); // Get new file name
				}
			}
			if (!$this->sector_pic->Upload->KeepFile) {
				if (!ew_Empty($this->sector_pic->Upload->Value)) {
					$rsnew['sector_pic'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->sector_pic->UploadPath), $rsnew['sector_pic']); // Get new file name
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
					if (!$this->sector_icon->Upload->KeepFile) {
						if (!ew_Empty($this->sector_icon->Upload->Value)) {
							$this->sector_icon->Upload->SaveToFile($this->sector_icon->UploadPath, $rsnew['sector_icon'], TRUE);
						}
					}
					if (!$this->sector_pic->Upload->KeepFile) {
						if (!ew_Empty($this->sector_pic->Upload->Value)) {
							$this->sector_pic->Upload->SaveToFile($this->sector_pic->UploadPath, $rsnew['sector_pic'], TRUE);
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

		// sector_icon
		ew_CleanUploadTempPath($this->sector_icon, $this->sector_icon->Upload->Index);

		// sector_pic
		ew_CleanUploadTempPath($this->sector_pic, $this->sector_pic->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "sectorslist.php", "", $this->TableVar, TRUE);
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
if (!isset($sectors_edit)) $sectors_edit = new csectors_edit();

// Page init
$sectors_edit->Page_Init();

// Page main
$sectors_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sectors_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsectorsedit = new ew_Form("fsectorsedit", "edit");

// Validate form
fsectorsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_sector_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sectors->sector_title->FldCaption(), $sectors->sector_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sector_brief");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sectors->sector_brief->FldCaption(), $sectors->sector_brief->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sector_details");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sectors->sector_details->FldCaption(), $sectors->sector_details->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_sector_icon");
			elm = this.GetElements("fn_x" + infix + "_sector_icon");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $sectors->sector_icon->FldCaption(), $sectors->sector_icon->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_sector_pic");
			elm = this.GetElements("fn_x" + infix + "_sector_pic");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $sectors->sector_pic->FldCaption(), $sectors->sector_pic->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sector_keywrds");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sectors->sector_keywrds->FldCaption(), $sectors->sector_keywrds->ReqErrMsg)) ?>");

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
fsectorsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsectorsedit.ValidateRequired = true;
<?php } else { ?>
fsectorsedit.ValidateRequired = false; 
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
<?php $sectors_edit->ShowPageHeader(); ?>
<?php
$sectors_edit->ShowMessage();
?>
<form name="fsectorsedit" id="fsectorsedit" class="<?php echo $sectors_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sectors_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sectors_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sectors">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($sectors->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_sectors_id" class="col-sm-2 control-label ewLabel"><?php echo $sectors->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->id->CellAttributes() ?>>
<span id="el_sectors_id">
<span<?php echo $sectors->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sectors->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sectors" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($sectors->id->CurrentValue) ?>">
<?php echo $sectors->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sectors->sector_title->Visible) { // sector_title ?>
	<div id="r_sector_title" class="form-group">
		<label id="elh_sectors_sector_title" for="x_sector_title" class="col-sm-2 control-label ewLabel"><?php echo $sectors->sector_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->sector_title->CellAttributes() ?>>
<span id="el_sectors_sector_title">
<input type="text" data-table="sectors" data-field="x_sector_title" name="x_sector_title" id="x_sector_title" size="70" maxlength="200" placeholder="<?php echo ew_HtmlEncode($sectors->sector_title->getPlaceHolder()) ?>" value="<?php echo $sectors->sector_title->EditValue ?>"<?php echo $sectors->sector_title->EditAttributes() ?>>
</span>
<?php echo $sectors->sector_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sectors->sector_brief->Visible) { // sector_brief ?>
	<div id="r_sector_brief" class="form-group">
		<label id="elh_sectors_sector_brief" for="x_sector_brief" class="col-sm-2 control-label ewLabel"><?php echo $sectors->sector_brief->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->sector_brief->CellAttributes() ?>>
<span id="el_sectors_sector_brief">
<input type="text" data-table="sectors" data-field="x_sector_brief" name="x_sector_brief" id="x_sector_brief" size="70" placeholder="<?php echo ew_HtmlEncode($sectors->sector_brief->getPlaceHolder()) ?>" value="<?php echo $sectors->sector_brief->EditValue ?>"<?php echo $sectors->sector_brief->EditAttributes() ?>>
</span>
<?php echo $sectors->sector_brief->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sectors->sector_details->Visible) { // sector_details ?>
	<div id="r_sector_details" class="form-group">
		<label id="elh_sectors_sector_details" for="x_details" class="col-sm-2 control-label ewLabel"><?php echo $sectors->sector_details->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->sector_details->CellAttributes() ?>>
<span id="el_sectors_sector_details">
<textarea data-table="sectors" data-field="x_sector_details" name="x_sector_details" id="x_details" cols="75" rows="4" placeholder="<?php echo ew_HtmlEncode($sectors->sector_details->getPlaceHolder()) ?>"<?php echo $sectors->sector_details->EditAttributes() ?>><?php echo $sectors->sector_details->EditValue ?></textarea>
</span>
<?php echo $sectors->sector_details->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sectors->sector_icon->Visible) { // sector_icon ?>
	<div id="r_sector_icon" class="form-group">
		<label id="elh_sectors_sector_icon" class="col-sm-2 control-label ewLabel"><?php echo $sectors->sector_icon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->sector_icon->CellAttributes() ?>>
<span id="el_sectors_sector_icon">
<div id="fd_x_sector_icon">
<span title="<?php echo $sectors->sector_icon->FldTitle() ? $sectors->sector_icon->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sectors->sector_icon->ReadOnly || $sectors->sector_icon->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sectors" data-field="x_sector_icon" name="x_sector_icon" id="x_sector_icon"<?php echo $sectors->sector_icon->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_sector_icon" id= "fn_x_sector_icon" value="<?php echo $sectors->sector_icon->Upload->FileName ?>">
<?php if (@$_POST["fa_x_sector_icon"] == "0") { ?>
<input type="hidden" name="fa_x_sector_icon" id= "fa_x_sector_icon" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_sector_icon" id= "fa_x_sector_icon" value="1">
<?php } ?>
<input type="hidden" name="fs_x_sector_icon" id= "fs_x_sector_icon" value="200">
<input type="hidden" name="fx_x_sector_icon" id= "fx_x_sector_icon" value="<?php echo $sectors->sector_icon->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_sector_icon" id= "fm_x_sector_icon" value="<?php echo $sectors->sector_icon->UploadMaxFileSize ?>">
</div>
<table id="ft_x_sector_icon" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $sectors->sector_icon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sectors->sector_pic->Visible) { // sector_pic ?>
	<div id="r_sector_pic" class="form-group">
		<label id="elh_sectors_sector_pic" class="col-sm-2 control-label ewLabel"><?php echo $sectors->sector_pic->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->sector_pic->CellAttributes() ?>>
<span id="el_sectors_sector_pic">
<div id="fd_x_sector_pic">
<span title="<?php echo $sectors->sector_pic->FldTitle() ? $sectors->sector_pic->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sectors->sector_pic->ReadOnly || $sectors->sector_pic->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sectors" data-field="x_sector_pic" name="x_sector_pic" id="x_sector_pic"<?php echo $sectors->sector_pic->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_sector_pic" id= "fn_x_sector_pic" value="<?php echo $sectors->sector_pic->Upload->FileName ?>">
<?php if (@$_POST["fa_x_sector_pic"] == "0") { ?>
<input type="hidden" name="fa_x_sector_pic" id= "fa_x_sector_pic" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_sector_pic" id= "fa_x_sector_pic" value="1">
<?php } ?>
<input type="hidden" name="fs_x_sector_pic" id= "fs_x_sector_pic" value="200">
<input type="hidden" name="fx_x_sector_pic" id= "fx_x_sector_pic" value="<?php echo $sectors->sector_pic->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_sector_pic" id= "fm_x_sector_pic" value="<?php echo $sectors->sector_pic->UploadMaxFileSize ?>">
</div>
<table id="ft_x_sector_pic" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $sectors->sector_pic->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sectors->sector_keywrds->Visible) { // sector_keywrds ?>
	<div id="r_sector_keywrds" class="form-group">
		<label id="elh_sectors_sector_keywrds" for="x_sector_keywrds" class="col-sm-2 control-label ewLabel"><?php echo $sectors->sector_keywrds->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sectors->sector_keywrds->CellAttributes() ?>>
<span id="el_sectors_sector_keywrds">
<input type="text" data-table="sectors" data-field="x_sector_keywrds" name="x_sector_keywrds" id="x_sector_keywrds" size="70" placeholder="<?php echo ew_HtmlEncode($sectors->sector_keywrds->getPlaceHolder()) ?>" value="<?php echo $sectors->sector_keywrds->EditValue ?>"<?php echo $sectors->sector_keywrds->EditAttributes() ?>>
</span>
<?php echo $sectors->sector_keywrds->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sectors_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsectorsedit.Init();
</script>
<?php
$sectors_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sectors_edit->Page_Terminate();
?>
