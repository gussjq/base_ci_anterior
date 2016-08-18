<?php $this->load->view("layout/javascript_base_view"); ?>
<?php script_tag("plugins/nestedsortable/jquery.mjs.nestedSortable"); ?>

<style type="text/css" >



    .placeholder {
        outline: 1px dashed #4183C4;
        /*-webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin: -1px;*/
    }

    .mjs-nestedSortable-error {
        background: #fbe3e4;
        border-color: transparent;
    }

    ol {
        margin: 0;
        padding: 0;
        padding-left: 30px;
    }

    ol.sortable, ol.sortable ol {
        margin: 0 0 0 25px;
        padding: 0;
        list-style-type: none;
    }

    ol.sortable {
        margin: 4em 0;
    }

    .sortable li {
        margin: 5px 0 0 0;
        padding: 0;
    }

    .sortable li div  {
        border: 1px solid #d4d4d4;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border-color: #D4D4D4 #D4D4D4 #BCBCBC;
        padding: 6px;
        margin: 0;
        cursor: move;
        background: #f6f6f6;
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
    }

    .sortable li.mjs-nestedSortable-branch div {
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#f0ece9 100%);

    }

    .sortable li.mjs-nestedSortable-leaf div {
        background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
        background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#bcccbc 100%);

    }

    li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
        border-color: #999;
        background: #fafafa;
    }

    .disclose {
        cursor: pointer;
        width: 10px;
        display: none;
    }

    .sortable li.mjs-nestedSortable-collapsed > ol {
        display: none;
    }

    .sortable li.mjs-nestedSortable-branch > div > .disclose {
        display: inline-block;
    }

    .sortable li.mjs-nestedSortable-collapsed > div > .disclose > span:before {
        content: '+ ';
    }

    .sortable li.mjs-nestedSortable-expanded > div > .disclose > span:before {
        content: '- ';
    }


</style>

<script type="text/javascript" >
    $(document).ready(function() {
        $('.sortable').nestedSortable({
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 3,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: true
        });

        $('.disclose').on('click', function() {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
        })
    });

</script>


<section id="demo">
    <ol class="sortable ui-sortable">
        <li id="list_1" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded">
            <div>
                <span class="disclose">
                    <span></span>
                </span>Item 1
            </div>
            <ol>
                <li id="list_2" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded"><div><span class="disclose"><span></span></span>Sub Item 1.1</div>
                    <ol>
                        <li id="list_3" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>Sub Item 1.2</div>
                        </li></ol>
                </li></ol>
        </li>
        <li id="list_4" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>Item 2</div>
        </li><li id="list_5" class="mjs-nestedSortable-branch mjs-nestedSortable-collapsed"><div><span class="disclose"><span></span></span>Item 3</div>
            <ol>
                <li id="list_6" class="mjs-nestedSortable-no-nesting mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>Sub Item 3.1 (no nesting)</div>
                </li><li id="list_7" class="mjs-nestedSortable-branch mjs-nestedSortable-collapsed"><div><span class="disclose"><span></span></span>Sub Item 3.2</div>
                    <ol>
                        <li id="list_8" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>Sub Item 3.2.1</div>
                        </li></ol>
                </li></ol>
        </li><li id="list_9" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>Item 4</div>
        </li><li id="list_10" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>Item 5</div>
        </li>
    </ol>
</section>