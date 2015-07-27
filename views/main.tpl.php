<?=$before_widget?>
<?=$before_title . $title . $after_title?>

    <form id="td-delivery-check" action="">
        <p>
            <input type="text" name="td_track_code" id="td-track-code" placeholder="<?=__('Enter the track code', TD_LANG_DOMAIN)?>">
            <input type="submit" value=">">
        </p>

        <p>
            <input type="radio" name="td_delivery_service" id="td-new-post" value="new_post" checked="checked">
                <label for="td-new-post"><?=__('New Post', TD_LANG_DOMAIN)?></label><br>
            <input type="radio" name="td_delivery_service" id="td-ukr-post" value="ukr_post">
                <label for="td-ukr-post"><?=__('Ukrpost', TD_LANG_DOMAIN)?></label><br>
        </p>
    </form>

    <div>
        <span id="td-track-response"></span>
    </div>

<?=$after_widget;?>