<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/stats.png" align="absmiddle"/>&nbsp;Welcome to the Community!</span>
    </h2>
    <div class="art-PostContent">


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
  
  

<!--<h2><img src="/img/icons/latest.png" align="absmiddle"/>&nbsp;Latest events</h2>

<table class="commentsTable">-->
<?php 

/*$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                 "'<[/!]*?[^<>]*?>'si",          // Strip out HTML tags
                 "'([rn])[s]+'",                // Strip out white space
                 "'&(quot|#34);'i",                // Replace HTML entities
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(copy|#169);'i",
                 "'&#(d+);'e");                    // evaluate as php

$replace = array ("",
			 "",
			 "\1",
			 "\"",
			 "&",
			 "<",
			 ">",
			 " ",
			 chr(161),
			 chr(162),
			 chr(163),
			 chr(169),
			 "chr(\1)");

 foreach($latest_events->result() as $event):
	$array = preg_split("/,/", timespan($event->timestamp, time()));
	$ago = strtolower($array[0]);
	$additional_info = $event->additional_info;
	if (strlen($additional_info) > 50) {
		$additional_info = substr($additional_info, 0, 49)."...";
	}	
	$additional_info = preg_replace($search, $replace, $additional_info); 
	if ($event->element_type == "new idea") {
		$icon = "ideas.png";
		$label = "<font color=orange>$ago ago</font><br/>submitted a new idea: ".anchor("idea/view/$event->element_id", $event->element_name);
	}
	elseif ($event->element_type == "edited idea") {
		$icon = "ideas.png";
		$label = "<font color=orange>$ago ago</font><br/>edited the idea: ".anchor("idea/view/$event->element_id", $event->element_name);
	}
	elseif ($event->element_type == "new hardware") {
		$icon = "hardware.png";
		$label = "<font color=orange>$ago ago</font><br/>registered new hardware: ".anchor("hardware/view/$event->element_id", $event->element_name)."<br/><font size=1 color=#999999><i>$additional_info ($event->additional_info2)</i></font>";
	}
	elseif ($event->element_type == "edited hardware") {
		$icon = "hardware.png";
		$label = "<font color=orange>$ago ago</font><br/>edited the hardware device: ".anchor("hardware/view/$event->element_id", $event->element_name)."<br/><font size=1 color=#999999><i>$additional_info ($event->additional_info2)</i></font>";
	}
	elseif ($event->element_type == "software review") {
		$icon = "software.png";
		$label = "<font color=orange>$ago ago</font><br/>reviewed ".anchor("software/view/$event->element_id", $event->element_name)."<br/><font size=1 color=#999999><i>".$additional_info."</i></font>";
	}
	elseif ($event->element_type == "idea comment") {
		$icon = "ideas.png";
		$label = "<font color=orange>$ago ago</font><br/>commented the idea ".anchor("idea/view/$event->element_id", "$event->element_name")."<br/><font size=1 color=#999999><i>".$additional_info."</i></font>";
	}
	elseif ($event->element_type == "new tutorial") {
		$icon = "tutorials.png";
		$label = "<font color=orange>$ago ago</font><br/>submitted a new tutorial: ".anchor("tutorial/view/$event->element_id", $event->element_name);
	}
	elseif ($event->element_type == "edited tutorial") {
		$icon = "tutorials.png";
		$label = "<font color=orange>$ago ago</font><br/>edited the tutorial: ".anchor("tutorial/view/$event->element_id", $event->element_name);
	}
	elseif ($event->element_type == "tutorial comment") {
		$icon = "tutorials.png";
		$label = "<font color=orange>$ago ago</font><br/>commented the tutorial ".anchor("tutorial/view/$event->element_id", "$event->element_name")."<br/><font size=1 color=#999999><i>".$additional_info."</i></font>";
	}
	$avtr = '/img/default_avatar.jpg';
	if (file_exists(FCPATH.'uploads/avatars/'.$event->user_id.".jpg")) {
		$avtr = '/uploads/avatars/'.$event->user_id.".jpg";
    }
    */
	?>
	
<!--	<tr>
		<td nowrap><img src="/img/menu/<?=$icon?>" height="12"/></td>
		<td>
			<center>
			<?=anchor("user/view/$event->user_id", "<img height=30 src=$avtr>")?><br/>
			<?=anchor("user/view/$event->user_id", "$event->user_name")?>
			</center>
		</td>
		<td><?=$label?></td>
	</tr>
    -->
<?php //endforeach;?>
<!--</table>-->

<table width="100%">
<tr>
    <td colspan="2">       
        <b>General stats</b>
        <ul>
        <li>Website users: <?=$num_users?></li>
        <li>Software reviews: <?=$num_software?></li>
        <li>Hardware devices: <?=$num_hardware?></li>
        <li>Ideas: <?=$num_ideas?></li>
        <li>Tutorials: <?=$num_tutorials?></li>
        </ul>
    </td>
</tr>
<tr>
    <td colspan="2">
        <b>Users by country</b>
        <div align="center" id="map_canvas"></div>
    </td>
</tr>
<tr>
    <td>
        <b>Users by release</b>
        <div align="center" id="release_chart"></div>
    </td>
    <td>
        <b>Users by edition</b>
        <div align="center" id="edition_chart"></div>
    </td>
</tr>
</table>


</div>
    <div class="cleared"></div>
</div>

</div>
</div>
</div>

<script type='text/javascript'>
   google.load('visualization', '1', {'packages': ['geochart']});
   google.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap() {
      var data = new google.visualization.DataTable();
      data.addRows(<?=$countries->num_rows()?>);
      data.addColumn('string', 'Country');
      data.addColumn('number', 'Users');
      
       <?php 
        $i = 0;
        foreach($countries->result() as $country): ?>
            data.setValue(<?=$i?>, 0, '<?=$country->name?>');
            data.setValue(<?=$i?>, 1, <?=$country->users?>);                        
        <?php 
            $i++;
        endforeach;?>
                

      var options = {};

      var container = document.getElementById('map_canvas');
      var geochart = new google.visualization.GeoChart(container);
      geochart.draw(data, options);
  };
  </script>
  
  <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Edition');
        data.addColumn('number', 'Number of users');
        data.addRows(<?=$editions->num_rows()?>);
        
        <?php 
        $i = 0;
        foreach($editions->result() as $edition): ?>
            data.setValue(<?=$i?>, 0, '<?=$edition->name?>: <?=$edition->users?>');
            data.setValue(<?=$i?>, 1, <?=$edition->users?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('edition_chart'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Release');
        data.addColumn('number', 'Number of users');
        data.addRows(<?=$releases->num_rows()?>);
        
        <?php 
        $i = 0;
        foreach($releases->result() as $release): ?>
            data.setValue(<?=$i?>, 0, '<?=$release->name?>: <?=$release->users?>');
            data.setValue(<?=$i?>, 1, <?=$release->users?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('release_chart'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>
