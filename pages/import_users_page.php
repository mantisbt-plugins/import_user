<?php
	# Mantis - a php based bugtracking system
	require_once( 'core.php' );
	access_ensure_project_level( ADMINISTRATOR );
	layout_page_header( lang_get( 'manage_import_users' ) );
	layout_page_begin( ); 
	print_manage_menu();
	$import_file='';
?>
<br />
<form method="post" enctype="multipart/form-data" action="<?php echo plugin_page('import_users')?>">
<input type="hidden" name="import_file" value="<?php echo $import_file;  ?>">

    <div align="center">
        <table class="width50" cellspacing="1">
            <tr>
            	<td class="form-title" colspan="2">
<?php
                    echo lang_get( 'import_users_file' )
?>
            	</td>
            </tr>
            <tr class="row-1">
                <td class="category" style="text-align:center">
                    <input name="edt_cell_separator" type="text" size="15" maxlength="1" value="<?php echo config_get( 'csv_separator' )?>" style="text-align:center">
                </td>
                <td>
                    <?php echo lang_get( 'import_file_format_col_spacer' ) ?>
                </td>
            </tr>
            <tr class="row-1">
                <td class="category" colspan="1" style="text-align:center">
                    <input type="checkbox" name="cb_skip_first_line" value="1" checked>
                </td>
                <td>
                    <?php echo lang_get( 'import_skip_first_line' ) ?>
                </td>
            </tr>

            <tr class="row-1">
            	<td class="category" width="15%">
            		<?php echo lang_get( 'select_file' ) ?><br />
             	</td>
            	<td width="85%" colspan="2">
            		<input type="file" name="import_file" accept=".csv" size="40" />
            		<input type="submit" class="button" value="<?php echo lang_get( 'process_file_button' ) ?>" />
            	</td>
            </tr>
        </table>
    </div>
</form>

<?php 
layout_page_end();