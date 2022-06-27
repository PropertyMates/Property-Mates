<?php if(get_message('error') || get_message('success') || get_message('warning')): ?>
<div class="row">
    <div class="col-md-12">
        <?php if(get_message('error') != null): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo get_message('error'); ?>
        </div>
        <?php endif; ?>

        <?php if(get_message('success') != null): ?>
            <div class="alert alert-success" role="alert">
                <?php echo get_message('success'); ?>
            </div>
        <?php endif; ?>

        <?php if(get_message('warning') != null): ?>
            <div class="alert alert-warning" role="alert">
                <?php echo get_message('warning'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

