{block name="widgets_emotion_components_vimeo_element"}
    <div id="congrats-user">

        {if !$Data.manufacturers}
            <span>No manufacturers found!</span>
        {else}
{*            <span>Congratulations! <br> You've created your own element!</span>*}
             <pre>{print_r($Data.manufacturers,1)}</pre>
            {foreach $Data.manufacturers as $key => $value}
                <p> {$key} </p>
                <p> {print_r($value,1)} </p>
            {/foreach}
        {/if}
    </div>
{/block}