<?php


function category_select_value($array, $parentid, $cat_array, $count) {
$count++;
foreach ($array[$parentid] as $cat) {


if(isset($cat_array) && in_array($cat->name, $cat_array)) {

echo "<option value=\"".$cat->name."\" checked=\"checked\" selected>";
} else {
echo "<option value=\"".$cat->name."\">";
}

for ($i=0;$i<$count;$i++)
echo "&nbsp;&nbsp;";

echo $cat->name."</option>";
//echo $cat->name."<br>";
if (array_key_exists ($cat->term_id, $array))
category_select_value($array, $cat->term_id, $cat_array, $count);
}
}

global $wpdb;

$blog_cat = $cat_exclude;
/*
if(is_array($blog_cat) && $blog_cat[0]!=''){
$blog_cat = get_blog_sub_cats_str($type='string');
}else{
$blog_cat = '';
}
if($blog_cat)
{
$blog_cat .= ",1";
}else
{
$blog_cat .= "1";
}*/
global $price_db_table_name;
if($_REQUEST['pkg']){
$pkg_id = mysql_real_escape_string($_REQUEST['pkg']);
$package_cats = $wpdb->get_var("select cat from $price_db_table_name where pid=$pkg_id");
}
if($package_cats)
{
if($blog_cat){
$blog_cat .= ",".$package_cats;
}else
{
$blog_cat .= $package_cats;
}
}
if($blog_cat)
{
$substr = " and c.term_id not in ($blog_cat)";
}

$catsql = "select c.term_id, c.name, tt.parent from $wpdb->terms c,$wpdb->term_taxonomy tt where tt.term_id=c.term_id and tt.taxonomy='placecategory' $substr order by c.name";
$catinfo = $wpdb->get_results($catsql);

$parentsql = "select c.term_id, c.name, tt.parent from $wpdb->terms c,$wpdb->term_taxonomy tt where tt.term_id=c.term_id and tt.taxonomy='placecategory' $substr order by tt.parent, c.name";
$parentinfo = $wpdb->get_results($parentsql);

$categories=array();

foreach ($parentinfo as $key=>$info) {
$categories[$info->parent][$info->term_id]=$info;
}

global $cat_array;
if($catinfo) {
$cat_display=get_option('ptthemes_category_dislay');
if($cat_display==''){$cat_display='checkbox';}
$counter = 0;
if($cat_display=='select'){?>
<div class="form_cat" >
<select name="place[]" id="category_<?php echo $counter;?>" class="textfield" >
<?php
category_select_value ($categories, 0, $cat_array, 0);
}
foreach($catinfo as $catinfo_obj) {
$counter++;
$termid = $catinfo_obj->term_id;
$name = $catinfo_obj->name;
if($cat_display=='checkbox'){
?>

<?php
}elseif($cat_display=='radio'){
?>
<?php
}elseif($cat_display=='select') {
continue;?>
<option <?php if(isset($cat_array) && in_array($name,$cat_array)){
echo 'selected="selected"'; }?> value="<?php echo $name; ?>">
<?php echo "$name"; ?></option>
<?php
}
}
if($cat_display=='select'){?>
</select></div>
<?php }
}
?><?php
$cat_display=get_option('ptthemes_category_dislay');
if($cat_display=='checkbox') { 
 $limit_code ='';
if($cat_limit){?>
<script type="text/javascript">
/*<![CDATA[*/
var checked = 0; 
function addCheck(box)  
{ 
    // allow checked box to be unchecked 
    if(!box.checked) return true; 
    // get ref to collection // see Alt: 
    var boxes = document.getElementsByName(box.name); 
    // count checked 
    var cb, count=0, k=0; 

    while(cb=boxes[k++]) 
        if(cb.checked && ++count><?php echo $cat_limit; ?>){ 
            alert("Sorry, you can select only <?php echo $cat_limit; ?> categories with this package."); 
            return false;        
        } 
    return true; 
}  
/*]]>*/
</script>
<?php $limit_code = 'onclick="return addCheck(this);"';}
 $args=array(
						  'orderby' => 'name',
						  'include' => $catstring,
						  'exclude' => $blog_cat,
						  'hide_empty'=> 0,
						  'parent'=>0,
						  'taxonomy'=> 'placecategory',
						  
						  );
	$counter=0;
	$categories=get_categories($args);
	foreach ($categories as $category)
	{
	 $counter++;
 ?>

<div class="form_cat" style="width:400px;"  ><label><input type="checkbox"  name="s"  <?php echo $limit_code; ?> id="category_<?php echo $counter;?>" value="<?php echo $category->name; ?>" class="checkbox" <?php if(isset($cat_array) && in_array($category->name,$cat_array)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $category->name;?></label></div>
<div class="togglecats" id="togglecatscategory_<?php echo $counter;?>">
	<?php $args=array(
						  'orderby' => 'name',
						  'include' => $catstring,
						  'exclude' => $blog_cat,
						  'hide_empty'=> 0,
						  'taxonomy'=> 'placecategory',
						  'parent'=>$category->term_id,						
						  
						  );

		 $subcategories=get_categories($args);
			foreach ($subcategories as $subcategory)
			{
			 $counter++;
	 ?>
     	<div class="form_subcat" style="width:400px; padding-left:15px;"  ><label><input type="checkbox"  name="s"   <?php echo $limit_code; ?> id="category_<?php echo $counter;?>" value="<?php echo $subcategory->name; ?>" class="checkbox" <?php if(isset($cat_array) && in_array($subcategory->name,$cat_array)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $subcategory->name;?></label></div>
	<?php   } ?></div>
<?php }
} ?>


<?php
//echo '###'.$cat_exclude.'###'.$blog_cat;
$cat_display=get_option('ptthemes_category_dislay');
if($cat_display=='radio')
{
 $args=array(
						  'orderby' => 'name',
						  'include' => $catstring,
						  'exclude' => $blog_cat,
						  'hide_empty'=> 0,
						  'taxonomy'=> 'placecategory',
						  'parent'=>0,						
						  
						  );
	$counter=0;
	$categories=get_categories($args);
	foreach ($categories as $category)
	{
	 $counter++;
 ?>

<div class="form_cat" style="width:400px;"  ><label><input type="radio"  name="s"   id="category_<?php echo $counter;?>" value="<?php echo $category->name; ?>" class="checkbox" <?php if(isset($cat_array) && in_array($category->name,$cat_array)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $category->name;?></label></div>
<div class="togglecats" id="togglecats<?php echo $category->name; ?>">
	<?php $args=array(
						  'orderby' => 'name',
						  'include' => $catstring,
						  'exclude' => $blog_cat,
						  'hide_empty'=> 0,
						  'parent'=>$category->term_id,						
						  
						  );

		 $subcategories=get_categories($args);
			foreach ($subcategories as $subcategory)
			{
			 $counter++;
	 ?>
     	<div class="form_subcat" style="width:400px; padding-left:15px;"  ><label><input type="radio" name="s" id="category_<?php echo $counter;?>" value="<?php echo $subcategory->name; ?>" class="checkbox" <?php if(isset($cat_array) && in_array($subcategory->term_id,$cat_array)){echo 'checked="checked"'; }?> />&nbsp;<?php echo $subcategory->name;?></label></div>
	<?php   } ?></div>
<?php }
} ?>


