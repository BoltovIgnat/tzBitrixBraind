<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (array_key_exists("COMPONENT_VERSION", $arParams) && $arParams["COMPONENT_VERSION"] == 2)
{
	if (strlen($arResult["FatalErrorMessage"]) > 0)
	{
		ShowError($arResult["FatalErrorMessage"]);
	}
	else
	{
		if (strlen($arResult["ErrorMessage"]) > 0)
		{
			ShowError($arResult["ErrorMessage"]);
		}

		$APPLICATION->IncludeComponent(
			"bitrix:main.interface.grid",
			"",
			array(
				"GRID_ID"=>$arResult["GRID_ID"],
				"HEADERS"=>$arResult["HEADERS"],
				"SORT"=>$arResult["SORT"],
				"ROWS"=>$arResult["RECORDS"],
				"FOOTER"=>array(array("title"=>GetMessage("BPWC_WLCT_TOTAL"), "value"=>count($arResult["RECORDS"]))),
				"ACTIONS"=>array("delete"=>true, "list"=>array()),
				"ACTION_ALL_ROWS"=>false,
				"EDITABLE"=>false,
				"NAV_OBJECT"=>null,
				"AJAX_MODE"=>$arResult["AJAX_MODE"],
				"AJAX_OPTION_JUMP"=>"N",
				"FILTER"=>$arResult["FILTER"],
			),
			$component
		);
	}
}
else
{
	if (!empty($arResult["ERROR_MESSAGE"])):
		ShowError($arResult["ERROR_MESSAGE"]);
	endif;
	?>
	<div class="bizproc-page-log">
		<div class="bizproc-item-title bizproc-workflow-state-template-name">
			<?=htmlspecialcharsbx($arResult["arWorkflowState"]["TEMPLATE_NAME"]) ?>
		</div>
		<?
	if (!empty($arResult["arWorkflowState"]["STATE_MODIFIED"])):
		?>
		<div class="bizproc-item-date bizproc-workflow-state-modified">
			<label><?= GetMessage("BPABL_STATE_MODIFIED")?>:</label>
			<?=htmlspecialcharsbx($arResult["arWorkflowState"]["STATE_MODIFIED"])?>
		</div>
		<?
	endif;
	if (!empty($arResult["arWorkflowState"]["TEMPLATE_DESCRIPTION"])):
		?>
		<div class="bizproc-item-description bizproc-workflow-state-template-description">
			<?=htmlspecialcharsbx($arResult["arWorkflowState"]["TEMPLATE_DESCRIPTION"])?>
		</div>
		<?
	endif;
	if (strlen($arResult["arWorkflowState"]["STATE_NAME"]) > 0):
	?>
		<div class="bizproc-item-text bizproc-workflow-state-name">
			<label><?=GetMessage("BPABL_STATE_NAME")?>:</label>
			<?
			if (strlen($arResult["arWorkflowState"]["STATE_TITLE"]) > 0)
			{
				echo htmlspecialcharsbx($arResult["arWorkflowState"]["STATE_TITLE"])." (".htmlspecialcharsbx($arResult["arWorkflowState"]["STATE_NAME"]).")";
			}
			else
			{
				echo htmlspecialcharsbx($arResult["arWorkflowState"]["STATE_NAME"]);
			}
			?>
		</div>
	<?
	endif;
	?>
		<div class="bizproc-item-text bizproc-workflow-state-log">
			<label><?= GetMessage("BPABL_LOG")?>:</label>
			<div class="bizproc-workflow-state-log-data">
	<?
				$current_level = -1;
				foreach ($arResult["arWorkflowTrack"] as $track)
				{
					$strMessageTemplate = "";
					switch ($track["TYPE"])
					{
						case 1:
							$strMessageTemplate = GetMessage("BPABL_TYPE_1");
							break;
						case 2:
							$strMessageTemplate = GetMessage("BPABL_TYPE_2");
							break;
						case 3:
							$strMessageTemplate = GetMessage("BPABL_TYPE_3");
							break;
						case 4:
							$strMessageTemplate = GetMessage("BPABL_TYPE_4");
							break;
						case 5:
							$strMessageTemplate = GetMessage("BPABL_TYPE_5");
							break;
						default:
							$strMessageTemplate = GetMessage("BPABL_TYPE_6");
					}

					$name = (strlen($track["ACTION_TITLE"]) > 0 ? $track["ACTION_TITLE"]." (".$track["ACTION_NAME"].")" : $track["ACTION_NAME"]);

					switch ($track["EXECUTION_STATUS"])
					{
						case CBPActivityExecutionStatus::Initialized:
							$status = GetMessage("BPABL_STATUS_1");
							break;
						case CBPActivityExecutionStatus::Executing:
							$status = GetMessage("BPABL_STATUS_2");
							break;
						case CBPActivityExecutionStatus::Canceling:
							$status = GetMessage("BPABL_STATUS_3");
							break;
						case CBPActivityExecutionStatus::Closed:
							$status = GetMessage("BPABL_STATUS_4");
							break;
						case CBPActivityExecutionStatus::Faulting:
							$status = GetMessage("BPABL_STATUS_5");
							break;
						default:
							$status = GetMessage("BPABL_STATUS_6");
					}

					switch ($track["EXECUTION_RESULT"])
					{
						case CBPActivityExecutionResult::None:
							$result = GetMessage("BPABL_RES_1");
							break;
						case CBPActivityExecutionResult::Succeeded:
							$result = GetMessage("BPABL_RES_2");
							break;
						case CBPActivityExecutionResult::Canceled:
							$result = GetMessage("BPABL_RES_3");
							break;
						case CBPActivityExecutionResult::Faulted:
							$result = GetMessage("BPABL_RES_4");
							break;
						case CBPActivityExecutionResult::Uninitialized:
							$result = GetMessage("BPABL_RES_5");
							break;
						default:
							$status = GetMessage("BPABL_RES_6");
					}

					$note = ((strlen($track["ACTION_NOTE"]) > 0) ? ": ".$track["ACTION_NOTE"] : "");
					$track["LEVEL"] = intval($track["LEVEL"]);
					if ($current_level < $track["LEVEL"]):
	?>
					<ul class="bizproc-list bizproc-workflow-state-log-data">
	<?
					elseif ($current_level > $track["LEVEL"]):
	?>
					</ul>
	<?
					endif;
					$arPattern = array("#ACTIVITY#", "#STATUS#", "#RESULT#", "#NOTE#");
					$arReplace = array($name, $status, $result, $note);
					if (!empty($track["ACTION_NAME"]) && !empty($track["ACTION_TITLE"])):
						$arPattern[] = $track["ACTION_NAME"];
						$arReplace[] = $track["ACTION_TITLE"];
					endif;

					$current_level = $track["LEVEL"];
	?>
						<li class="bizproc-list-item bizproc-workflow-state-log-data-item">
							<?=str_replace(
							$arPattern,
							$arReplace,
							$strMessageTemplate)?></li>
	<?
				}
				?>
			</div>
		</div>
	</div>
	<?
}