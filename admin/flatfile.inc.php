<?php
// strip slashes
if (get_magic_quotes_gpc()) {
  function stripslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
    return $value;
  }

  $_POST = stripslashes_deep($_POST);
  $_GET = stripslashes_deep($_GET);
  //$_COOKIE = stripslashes_deep($_COOKIE);
}

$structure_tmp = file($structure_file);
$structure = array();
foreach($structure_tmp as $key=>$tmp) {
  /*if(strpos($tmp,':') === 0) {
    $code = explode(':',$tmp);
    ${$code[1]} = trim($code[2]);
    continue;
  }*/
  $line = explode(',',$tmp);
  $name_will_be = str_replace(' ','',trim($line[0]));
  foreach($structure as $key1=>$value1) {
    if ($value1['name'] == $name_will_be)
      die("Few columns have the similar name (not counting spaces): '{$line[0]}'. Please rename.");
  }
  $structure[$key]['name_original'] = trim($line[0]);
  $structure[$key]['name'] = str_replace(' ','',$structure[$key]['name_original']);
  $structure[$key]['type'] = trim($line[1]);
  if (isset($line[2])) $structure[$key]['format'] = trim($line[2]);
  if (isset($line[3])) {
    $values = explode(':',$line[3]);
    foreach($values as $item) {
      $structure[$key]['values'][] = trim($item);
    }
  }
}

// Save data (Submit button pressed)
if (isset($_POST['submit'])) {
  /////////////////////////////////////////////////

  if ($skip_lines > 0) {
    // read header lines
    $tmp_data = file($data_file);
  }

  $f = fopen($data_file,'w+');
  if ($f) {

    // save header back to file
    if ($skip_lines > 0) {
      for($i=0; $i < $skip_lines; $i++) {
        fputs($f,$tmp_data[$i]);
      }
    }

    for( $i=0; $i < count($_POST[$structure[0]['name']]); $i++ ) {
      // do not save records marked for delete
      if (isset($_POST['d_e_l_e_t_e'][$i])) continue;

      $s = '';
      $isfirst = true;
      foreach($structure as $key => $field) {
        $n1 = isset($_POST[$structure[$key]['name']]) ? $_POST[$structure[$key]['name']] : '';
        $v1 = isset($n1[$i]) ? $n1[$i] : $structure[$key]['values'][1];
        // remove new line characters as each new line represents new database row
        $v1 = str_replace(array("\r\n","\n","\r"),'',$v1);
        $s = $s . ($isfirst ? '' : $delimiter) . $v1;
        $isfirst = false;
      }
      // do not save empty lines
      if (trim(str_replace($delimiter,'',$s)) == '') continue;

      // save database record to file
      fputs($f,$s."\n");
    } // for
    fclose($f);
  } // if
}

$data = file($data_file);

// skip header lines
if ($skip_lines > 0) $data = array_slice($data, $skip_lines);

// add "new line" holder
$data[] = str_repeat($delimiter,count($structure)-1);

echo '<html>';
echo "<head><title></title></head>";
echo "<body><h1></h1>";
echo '<div align="center">';
echo '<form method="post">';
echo '<table>'."\n";

// output header
echo '<table id="table"><thead>';
foreach ($structure as $key=>$line) {
  echo "<th>{$line['name_original']}</th>";
}
echo '<th>Delete User</th>';
echo '</tr>'."\n";
echo '</tr></thead>';

// output data
foreach($data as $datakey => $line) {

  // skip empty rows
  if (trim($line) == '') continue;

  echo '<tr style="background: #'.($datakey % 2 == 0 ? 'F0F0F0' : 'FAFAFA').'">';

  $items = explode($delimiter,$line);

  // any fields not defined? add empty
  while (count($items) < count($structure))
    $items[] = '';

  foreach ($items as $key => $item) {
    $item = htmlspecialchars(trim($item));
    $name = $structure[$key]['name'];
    echo "\n".'  <td valign="top">';
    switch ($structure[$key]['type']) {
      case 'STRING':
        echo '<input onchange="cdf('.$datakey.')" name="'.$name.'['.$datakey.']" value="'.$item.'" size="'.$structure[$key]['format'].'" />';
        break;
	case 'PASSWORD':
        echo '<input onchange="cdf('.$datakey.')" name="'.$name.'['.$datakey.']" value="'.$item.'" size="'.$structure[$key]['format'].'" type="password" />';
        break;
      case 'TEXT':
        $rc = explode(':',$structure[$key]['format']);
        $cols = trim($rc[0]);
        $rows = trim($rc[1]);
        echo '<textarea onchange="cdf('.$datakey.')" name="'.$name.'['.$datakey.']" rows="'.$rows.'" cols="'.$cols.'">'.$item.'</textarea>';
        break;
      case 'LOGICAL':
        $val_yes = trim($structure[$key]['values'][0]);
        echo '<input onchange="cdf('.$datakey.')" name="'.$name.'['.$datakey.']" type="checkbox" value="'.$val_yes.'" />';
        break;
      case 'LIST':
        echo '<select onchange="cdf('.$datakey.')" name="'.$name.'['.$datakey.']" size="'.$structure[$key]['format'].'">';
        foreach($structure[$key]['values'] as $value) {
          echo '<option value="'.$value.'" '.($value == $item ? 'selected' : '').'>'.$value.'</option>';
        }
        echo '</select>';
        break;
    }
    echo '</td>';
  }
  
  // Mark for delete if last record (i.e. Add option). In this way we'll skip adding empty records
  echo "\n  <td><input id='d_e_l_e_t_e[{$datakey}]'  name='d_e_l_e_t_e[{$datakey}]' type='checkbox' /></td>";
  echo "\n</tr>\n";

}

echo '<tr><td colspan=255 align=center><input type="submit" name="submit" id="save" value="Save Changes"></td></tr>';
echo '</table>';
echo "</form>

<script>
function cdf(theid) {
document.getElementById('d_e_l_e_t_e['+theid+']').checked = false;
}
</script>";
echo '</div';
echo '</body>';
echo '</html>';

?>
