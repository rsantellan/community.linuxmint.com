<div class="art-content">
<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
    <h2 class="art-PostHeaderIcon-wrapper">
        <span class="art-PostHeader"><img src="/img/icons/hardware.png" align="absmiddle"/>&nbsp;Hardware</span>
    </h2>
    <div class="art-PostContent">

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<table>
<tr>
    <td valign="center" width="50%">
        <b>Hardware by level of compatibility</b>
        <div align="center" id="statuses_chart"></div>
    </td>

    <td valign="center" width="50%">
        <b>Hardware by brands</b>
        <div align="center" id="brands_chart"></div>
    </td>
</tr>
<tr>
    <td valign="center" width="50%">
        <b>Hardware by type</b>
        <div align="center" id="categories_chart"></div>
    </td>

    <td valign="center" width="50%">
        <b>Hardware by release</b>
        <div align="center" id="releases_chart"></div>
    </td>
</tr>
<tr>
<td colspan="2">
    <b>Search</b>

    <div align="center">
    <?php echo form_open("hardware/search"); ?>
        <table>
        <tr><th>Type:</th><td>
            <select name="search_hardware_category">
                    <option value="-1">Any</option>
                <?php foreach($categories->result() as $category):
                    echo "<option value=\"".$category->id."\" ";				
                    echo ">".$category->name."</option>";
                endforeach;?>
            </select></td></tr>
        <tr><th>Brand:</th><td>
            <select name="search_hardware_brand">
                    <option value="-1">Any</option>
                <?php foreach($brands->result() as $brand):
                    echo "<option value=\"".$brand->id."\" ";            
                    echo ">".$brand->name."</option>";
                endforeach;?>
            </select></td></tr>
        <tr><th>Status:</th><td>
            <select name="search_hardware_status">
                    <option value="-1">Any</option>
                <?php foreach($statuses->result() as $status):
                    echo "<option value=\"".$status->id."\" ";			
                    echo ">".$status->name."</option>";
                endforeach;?>
            </select></td></tr>
        <tr><th>Release:</th><td>
            <select name="search_hardware_release">
                    <option value="-1">Any</option>
                <?php foreach($releases->result() as $release):
                    echo "<option value=\"".$release->id."\" ";			
                    echo ">".$release->name."</option>";
                endforeach;?>
            </select></td></tr>
        <tr><th>Name:</th><td><input type="text" name="search_hardware_name"/></td></tr>
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
</table>


</div>
    <div class="cleared"></div>
</div>

</div>
</div>
</div>
        
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Status');
        data.addColumn('number', 'Number of devices');
        data.addRows(<?=$statuses_chart->num_rows()?>);
        
        <?php 
        $i = 0;
        foreach($statuses_chart->result() as $status): ?>
            data.setValue(<?=$i?>, 0, "<?=$status->status?>: <?=$status->number?>");
            data.setValue(<?=$i?>, 1, <?=$status->number?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('statuses_chart'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Brand');
        data.addColumn('number', 'Number of devices');
        data.addRows(<?=$brands_chart->num_rows()?>);
        
        <?php 
        $i = 0;
        foreach($brands_chart->result() as $brand): ?>
            data.setValue(<?=$i?>, 0, "<?=$brand->name?>: <?=$brand->number?>");
            data.setValue(<?=$i?>, 1, <?=$brand->number?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('brands_chart'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Number of devices');
        data.addRows(<?=$categories_chart->num_rows()?>);
        
        <?php 
        $i = 0;
        foreach($categories_chart->result() as $category): ?>
            data.setValue(<?=$i?>, 0, "<?=str_replace("\"", "\'", $category->name)?>: <?=$category->number?>");
            data.setValue(<?=$i?>, 1, <?=$category->number?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('categories_chart'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>
    
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Release');
        data.addColumn('number', 'Number of devices');
        data.addRows(<?=$releases_chart->num_rows()?>);
        
        <?php 
        $i = 0;
        foreach($releases_chart->result() as $release): ?>
            data.setValue(<?=$i?>, 0, "<?=$release->name?>: <?=$release->number?>");
            data.setValue(<?=$i?>, 1, <?=$release->number?>);                        
        <?php 
            $i++;
        endforeach;?>
                
        var chart = new google.visualization.PieChart(document.getElementById('releases_chart'));
        chart.draw(data, {width: 300, height: 200, chartArea:{left:10,top:20,width:"100%",height:"80%"}});
      }
    </script>
