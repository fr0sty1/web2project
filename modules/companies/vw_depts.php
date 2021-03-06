<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')) {
	die('You should not access this file directly.');
}

##
##	Companies: View Departments sub-table
##

global $AppUI, $company_id, $canEdit;

$depts = CCompany::getDepartments($AppUI, $company_id);

// function renamed to avoid naming clash
function showchilddept_comp(&$a, $level = 0) {
	global $AppUI;
	$s = '
	<td>
		<a href="./index.php?m=departments&amp;a=addedit&amp;dept_id=' . $a["dept_id"] . '" title="' . $AppUI->_('edit') . '">
			' . w2PshowImage('icons/stock_edit-16.png', 16, 16, '') . '
	</td>
	<td>';

	for ($y = 0; $y < $level; $y++) {
		if ($y + 1 == $level) {
			$s .= '<img src="' . w2PfindImage('corner-dots.gif') . '" width="16" height="12" border="0">';
		} else {
			$s .= '<img src="' . w2PfindImage('shim.gif') . '" width="16" height="12" border="0">';
		}
	}

	$s .= '<a href="./index.php?m=departments&a=view&dept_id=' . $a['dept_id'] . '">' . $a['dept_name'] . '</a>';
	$s .= '</td>';
	$s .= '<td align="center">' . ($a['dept_users'] ? $a['dept_users'] : '') . '</td>';

	echo '<tr>' . $s . '</tr>';
}

// function renamed to avoid naming clash
function findchilddept_comp(&$tarr, $parent, $level = 0) {
	$level = $level + 1;
	$n = count($tarr);
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['dept_parent'] == $parent && $tarr[$x]['dept_parent'] != $tarr[$x]['dept_id']) {
			showchilddept_comp($tarr[$x], $level);
			findchilddept_comp($tarr, $tarr[$x]['dept_id'], $level);
		}
	}
}

$s = '<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tbl">';
$s .= '<tr>';

if (count($depts)) {
	$s .= '<th>&nbsp;</th>';
	$s .= '<th width="100%">' . $AppUI->_('Name') . '</th>';
	$s .= '<th>' . $AppUI->_('Users') . '</th>';
} else {
	$s .= '<td>' . $AppUI->_('No data available') . '</td>';
}

$s .= '</tr>';
echo $s;

if (count($depts)) {
	foreach ($depts as $dept) {
		if ($dept['dept_parent'] == 0) {
			showchilddept_comp($dept);
			findchilddept_comp($depts, $dept['dept_id']);
		}
	}
}

echo '
<tr>
	<td colspan="3" nowrap="nowrap" rowspan="99" align="right" valign="top" style="background-color:#ffffff">';
if ($canEdit) {
	echo '<input type="button" class=button value="' . $AppUI->_('new department') . '" onclick="javascript:window.location=\'./index.php?m=departments&amp;a=addedit&amp;company_id=' . $company_id . '\';" />';
}
echo '
	</td>
</tr>
</table>';