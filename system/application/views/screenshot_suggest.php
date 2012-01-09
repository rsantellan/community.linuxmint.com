<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader">Suggest a screenshot</span>
    </h2>
    <div class="art-PostContent">

To get your screenshot approved, please check the following:
<ul>
<li>Avoid Windows or Mac screenshots</li>
<li>Avoid screenshots from other distributions (or using characteristic themes, such as Ubuntu's orange/brown Human theme)</li>
<li>Avoid screenshots of the entire desktop (if you're taking the screenshot in Linux Mint, select the application and press ALT+Print_Screen)</li>
</ul>

<?php
echo form_open_multipart("software/upload_screenshot/$pkg_name");
?>

<table>
<tr><th>Screenshot</th><td><input type="file" name="screenshot" size="20"> (.png format, 500KB max) </td></tr>

<td colspan="2" align="center"><span class="art-button-wrapper">
                	<span class="l"> </span>
                	<span class="r"> </span>
                	<input class="art-button" type="submit" name="submit" value="Upload"/>
</span></td></tr>
</table>
</form> 
      
            
    </div>
    <div class="cleared"></div>
</div>

    </div>
</div>
</div>
</div>
