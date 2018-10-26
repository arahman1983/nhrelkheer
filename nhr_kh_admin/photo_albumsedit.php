<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "photo_albumsinfo.php" ?>
<?php include_once "admin_usersinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$photo_albums_edit = NULL; // Initialize page object first

class cphoto_albums_edit extends cphoto_albums {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{2740043E-02B0-4E15-B10B-02037850EEC7}";

	// Table name
	var $TableName = 'photo_albums';

	// Page object name
	var $PageObjName = 'photo_albums_edit';

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

		// Table object (photo_albums)
		if (!isset($GLOBALS["photo_albums"]) || get_class($GLOBALS["photo_albums"]) == "cphoto_albums") {
			$GLOBALS["photo_albums"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["photo_albums"];
		}

		// Table object (admin_users)
		if (!isset($GLOBALS['admin_users'])) $GLOBALS['admin_users'] = new cadmin_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'photo_albums', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("photo_albumslist.php"));
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
		global $EW_EXPORT, $photo_albums;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($photo_albums);
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
			$this->Page_Terminate("photo_albumslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("photo_albumslist.php"); // No matching record, return to list
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
		$this->album_Imge->Upload->Index = $objForm->Index;
		$this->album_Imge->Upload->UploadFile();
		$this->album_Imge->CurrentValue = $this->album_Imge->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->album_title->FldIsDetailKey) {
			$this->album_title->setFormValue($objForm->GetValue("x_album_title"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->album_title->CurrentValue = $this->album_title->FormValue;
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
		$this->album_title->setDbValue($rs->fields('album_title'));
		$this->album_Imge->Upload->DbValue = $rs->fields('album_Imge');
		$this->album_Imge->CurrentValue = $this->album_Imge->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->album_title->DbValue = $row['album_title'];
		$this->album_Imge->Upload->DbValue = $row['album_Imge'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// album_title
		// album_Imge

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// album_title
		$this->album_title->ViewValue = $this->album_title->CurrentValue;
		$this->album_title->ViewCustomAttributes = "";

		// album_Imge
		if (!ew_Empty($this->album_Imge->Upload->DbValue)) {
			$this->album_Imge->ImageAlt = $this->album_Imge->FldAlt();
			$this->album_Imge->ViewValue = $this->album_Imge->Upload->DbValue;
		} else {
			$this->album_Imge->ViewValue = "";
		}
		$this->album_Imge->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// album_title
			$this->album_title->LinkCustomAttributes = "";
			$this->album_title->HrefValue = "";
			$this->album_title->TooltipValue = "";

			// album_Imge
			$this->album_Imge->LinkCustomAttributes = "";
			if (!ew_Empty($this->album_Imge->Upload->DbValue)) {
				$this->album_Imge->HrefValue = ew_GetFileUploadUrl($this->album_Imge, $this->album_Imge->Upload->DbValue); // Add prefix/suffix
				$this->album_Imge->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->album_Imge->HrefValue = ew_ConvertFullUrl($this->album_Imge->HrefValue);
			} else {
				$this->album_Imge->HrefValue = "";
			}
			$this->album_Imge->HrefValue2 = $this->album_Imge->UploadPath . $this->album_Imge->Upload->DbValue;
			$this->album_Imge->TooltipValue = "";
			if ($this->album_Imge->UseColorbox) {
				$this->album_Imge->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->album_Imge->LinkAttrs["data-rel"] = "photo_albums_x_album_Imge";

				//$this->album_Imge->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->album_Imge->LinkAttrs["data-placement"] = "bottom";
				//$this->album_Imge->LinkAttrs["data-container"] = "body";

				$this->album_Imge->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// album_title
			$this->album_title->EditAttrs["class"] = "form-control";
			$this->album_title->EditCustomAttributes = "";
			$this->album_title->EditValue = ew_HtmlEncode($this->album_title->CurrentValue);
			$this->album_title->PlaceHolder = ew_RemoveHtml($this->album_title->FldCaption());

			// album_Imge
			$this->album_Imge->EditAttrs["class"] = "form-control";
			$this->album_Imge->EditCustomAttributes = "";
			if (!ew_Empty($this->album_Imge->Upload->DbValue)) {
				$this->album_Imge->ImageAlt = $this->album_Imge->FldAlt();
				$this->album_Imge->EditValue = $this->album_Imge->Upload->DbValue;
			} else {
				$this->album_Imge->EditValue = "";
			}
			if (!ew_Empty($this->album_Imge->CurrentValue))
				$this->album_Imge->Upload->FileName = $this->album_Imge->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->album_Imge);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// album_title
			$this->album_title->HrefValue = "";

			// album_Imge
			if (!ew_Empty($this->album_Imge->Upload->DbValue)) {
				$this->album_Imge->HrefValue = ew_GetFileUploadUrl($this->album_Imge, $this->album_Imge->Upload->DbValue); // Add prefix/suffix
				$this->album_Imge->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->album_Imge->HrefValue = ew_ConvertFullUrl($this->album_Imge->HrefValue);
			} else {
				$this->album_Imge->HrefValue = "";
			}
			$this->album_Imge->HrefValue2 = $this->album_Imge->UploadPath . $this->album_Imge->Upload->DbValue;
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
		if (!$this->album_title->FldIsDetailKey && !is_null($this->album_title->FormValue) && $this->album_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->album_title->FldCaption(), $this->album_title->ReqErrMsg));
		}
		if ($this->album_Imge->Upload->FileName == "" && !$this->album_Imge->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->album_Imge->FldCaption(), $this->album_Imge->ReqErrMsg));
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

			// album_title
			$this->album_title->SetDbValueDef($rsnew, $this->album_title->CurrentValue, "", $this->album_title->ReadOnly);

			// album_Imge
			if (!($this->album_Imge->ReadOnly) && !$this->album_Imge->Upload->KeepFile) {
				$this->album_Imge->Upload->DbValue = $rsold['album_Imge']; // Get original value
				if ($this->album_Imge->Upload->FileName == "") {
					$rsnew['album_Imge'] = NULL;
				} else {
					$rsnew['album_Imge'] = $this->album_Imge->Upload->FileName;
				}
			}
			if (!$this->album_Imge->Upload->KeepFile) {
				if (!ew_Empty($this->album_Imge->Upload->Value)) {
					$rsnew['album_Imge'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->album_Imge->UploadPath), $rsnew['album_Imge']); // Get new file name
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
					if (!$this->album_Imge->Upload->KeepFile) {
						if (!ew_Empty($this->album_Imge->Upload->Value)) {
							$this->album_Imge->Upload->SaveToFile($this->album_Imge->UploadPath, $rsnew['album_Imge'], TRUE);
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

		// album_Imge
		ew_CleanUploadTempPath($this->album_Imge, $this->album_Imge->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "photo_albumslist.php", "", $this->TableVar, TRUE);
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
if (!isset($photo_albums_edit)) $photo_albums_edit = new cphoto_albums_edit();

// Page init
$photo_albums_edit->Page_Init();

// Page main
$photo_albums_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$photo_albums_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fphoto_albumsedit = new ew_Form("fphoto_albumsedit", "edit");

// Validate form
fphoto_albumsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_album_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $photo_albums->album_title->FldCaption(), $photo_albums->album_title->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_album_Imge");
			elm = this.GetElements("fn_x" + infix + "_album_Imge");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $photo_albums->album_Imge->FldCaption(), $photo_albums->album_Imge->ReqErrMsg)) ?>");

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
fphoto_albumsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fphoto_albumsedit.ValidateRequired = true;
<?php } else { ?>
fphoto_albumsedit.ValidateRequired = false; 
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
<?php $photo_albums_edit->ShowPageHeader(); ?>
<?php
$photo_albums_edit->ShowMessage();
?>
<form name="fphoto_albumsedit" id="fphoto_albumsedit" class="<?php echo $photo_albums_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($photo_albums_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $photo_albums_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="photo_albums">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($photo_albums->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_photo_albums_id" class="col-sm-2 control-label ewLabel"><?php echo $photo_albums->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $photo_albums->id->CellAttributes() ?>>
<span id="el_photo_albums_id">
<span<?php echo $photo_albums->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $photo_albums->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="photo_albums" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($photo_albums->id->CurrentValue) ?>">
<?php echo $photo_albums->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($photo_albums->album_title->Visible) { // album_title ?>
	<div id="r_album_title" class="form-group">
		<label id="elh_photo_albums_album_title" for="x_album_title" class="col-sm-2 control-label ewLabel"><?php echo $photo_albums->album_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $photo_albums->album_title->CellAttributes() ?>>
<span id="el_photo_albums_album_title">
<input type="text" data-table="photo_albums" data-field="x_album_title" name="x_album_title" id="x_album_title" size="70" maxlength="200" placeholder="<?php echo ew_HtmlEncode($photo_albums->album_title->getPlaceHolder()) ?>" value="<?php echo $photo_albums->album_title->EditValue ?>"<?php echo $photo_albums->album_title->EditAttributes() ?>>
</span>
<?php echo $photo_albums->album_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($photo_albums->album_Imge->Visible) { // album_Imge ?>
	<div id="r_album_Imge" class="form-group">
		<label id="elh_photo_albums_album_Imge" class="col-sm-2 control-label ewLabel"><?php echo $photo_albums->album_Imge->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $photo_albums->album_Imge->CellAttributes() ?>>
<span id="el_photo_albums_album_Imge">
<div id="fd_x_album_Imge">
<span title="<?php echo $photo_albums->album_Imge->FldTitle() ? $photo_albums->album_Imge->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($photo_albums->album_Imge->ReadOnly || $photo_albums->album_Imge->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="photo_albums" data-field="x_album_Imge" name="x_album_Imge" id="x_album_Imge"<?php echo $photo_albums->album_Imge->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_album_Imge" id= "fn_x_album_Imge" value="<?php echo $photo_albums->album_Imge->Upload->FileName ?>">
<?php if (@$_POST["fa_x_album_Imge"] == "0") { ?>
<input type="hidden" name="fa_x_album_Imge" id= "fa_x_album_Imge" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_album_Imge" id= "fa_x_album_Imge" value="1">
<?php } ?>
<input type="hidden" name="fs_x_album_Imge" id= "fs_x_album_Imge" value="200">
<input type="hidden" name="fx_x_album_Imge" id= "fx_x_album_Imge" value="<?php echo $photo_albums->album_Imge->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_album_Imge" id= "fm_x_album_Imge" value="<?php echo $photo_albums->album_Imge->UploadMaxFileSize ?>">
</div>
<table id="ft_x_album_Imge" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $photo_albums->album_Imge->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $photo_albums_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fphoto_albumsedit.Init();
</script>
<?php
$photo_albums_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$photo_albums_edit->Page_Terminate();
?>
