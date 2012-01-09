<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/ideas.png" align="absmiddle"/>&nbsp;Ideas</span>
    </h2>
    <div class="art-PostContent">

<table>
<tr>

<td valign="center" width="50%">

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Status');
        data.addColumn('number', 'Number of ideas');
        data.addRows(5);
        
        <?php 
        $i = 0;
        foreach($statuses_chart->result() as $status): ?>
            data.setValue(<?=$i?>, 0, '<?=$status->status?>: <?=$status->number?>');
            data.setValue(<?=$i?>, 1, <?=$status->number?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>


<b>Ideas by status</b>

<div align="center" id="chart_div"></div>

</td>
<td valign="center" width="50%">

<b>Search</b>

<div align="center">
<?php echo form_open("idea/search"); ?>
	<table>
	<tr><td>Title:</td><td><input type="text" name="search_title"/></td></tr>
    <tr><td>Content:</td><td><input type="text" name="search_body"/></td></tr>
	<tr><td>Status:</td><td>
		<select name="search_status">
			<?php foreach($statuses->result() as $status):
				echo "<option value=\"".$status->id."\" ";				
				echo ">".$status->name."</option>";
			endforeach;?>
		</select></td></tr>
	<tr><td>Sort:</td><td>
		<select name="search_sort">
			<option value="0">Top Rated</option>
			<option value="1">Latest</option>
			<option value="2">Most Voted</option>
			<option value="3">Most Commented</option>
		</select></td></tr>
	<tr><td>Filter:</td><td>
		<select name="search_filter">
			<option value="0">All ideas</option>
			<option value="1">Ideas I didn't vote</option>
			<option value="2">Ideas I am promoting</option>
			<option value="3">Ideas I am demoting</option>
			<option value="4">Ideas I don't care about</option>
		</select></td></tr>
	<td colspan="2" align="center"><span class="art-button-wrapper">
		<span class="l"> </span>
		<span class="r"> </span>
		<input class="art-button" type="submit" name="search" value="Search"/>
	</span></td></tr>
	</table>
	</form> 
</div>
</td>
<tr>
<td colspan="2">
<b>Latest developments</b>
<ul>
<?php
foreach($latest_developments->result() as $idea):
?>
<li><font color="orange"><?php $array = preg_split("/,/", timespan($idea->last_status_changed, time()));  echo strtolower($array[0]);?> ago</font> <?=anchor("idea/view/$idea->id", "$idea->status: $idea->title")?> (score: <?=$idea->score?>)</li>
<?php
endforeach;
?>
</ul>

<b>Latest ideas</b>
<ul>
<?php
foreach($latest_ideas->result() as $idea):
?>
<li><font color="orange"><?php $array = preg_split("/,/", timespan($idea->created, time()));  echo strtolower($array[0]);?> ago</font> <?=anchor("idea/view/$idea->id", "$idea->title")?> (score: <?=$idea->score?>)</li>
<?php
endforeach;
?>
</ul>

<b>Most popular ideas</b>
<ol>
<?php
foreach($ideas->result() as $idea):
?>
<li><?=anchor("idea/view/$idea->id", "$idea->title")?> (score: <?=$idea->score?>)</li>
<?php
endforeach;
?>
</ol>

</td>
</tr>
</table>




</div>
    <div class="cleared"></div>
</div>

</div>
</div>
</div>
