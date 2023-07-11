<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .data-hide {
        display: none;
    }
    .data-hide:hover {
        display: block;
    }
    .myDIV:hover + .data-hide {
        display: block;
    }
    .mb-2 {
        margin-bottom: 2rem;
    }
    .mr-2 {
        margin-right: 2rem;
    }
    div.undo {
        position: fixed;
        top:1rem;
        left:35%;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div>
                            <textarea id="taLogs" style="background-color: black;color:white" readonly class="form-control" rows="30"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php init_tail(); ?>
<script>
    const logsURL = getRewriteBase() + '/server_api/server_api_report';

    $(function(){
        reloadData();
        setTimeout(reloadData, 3000 );
    });

    function reloadData()
    {
        $.getJSON( logsURL, function( data ) {
            if(data["error"]) {
                $('#taLogs').text('logları almaya çalışırken hata oluştu:' + data["error"]);
            } else {
                $('#taLogs').text(data['logs']);
                // if($('#taLogs').length){
                //     $('#taLogs').scrollTop($('#taLogs')[0].scrollHeight - $('#taLogs').height());
                // }
            }
        });
        setTimeout(reloadData, 3000 );
    };

    function getRewriteBase()
    {
        return window.location.pathname.split('/admin/')[0];
    }

</script>
</body>
</html>
