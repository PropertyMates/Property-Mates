<?php if(isset($notification)): ?>
<li>
    <div class="notification-item">
        <div class="message py-3">
            <div class="d-flex align-items-center">
                <?php if($notification->notify_type == 1): ?>

                    <div class="list-thumb">
                        <img src="<?php echo get_avatar_url($notification->sender_user); ?>" alt="">
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">
                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>"> <?php echo get_user_full_name($notification->sender_user); ?></a>
                                sent you a connection request
                            </div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
                        <div class="connect-btn">
                            <a href="#" data-id="<?php echo $notification->notify_id; ?>" class="notify-reject-action btn btn-orange-bordered rounded-pill">Deny</a>
                            <a href="#" data-id="<?php echo $notification->notify_id; ?>" class="notify-accept-action btn btn-orange rounded-pill">Accept</a>
                        </div>
                    </div>

                <?php elseif($notification->notify_type == 2 || $notification->notify_type == 9): ?>
                    <div class="list-thumb">
                        <img src="<?php echo get_avatar_url($notification->sender_user); ?>" alt="">
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">
                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>"> <?php echo get_user_full_name($notification->sender_user); ?></a>
                                <?php echo $notification->message; ?>
                            </div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
<!--                        <div class="connect-btn">-->
<!--                            <a href="--><?php //echo home_url(CO_OWNER_MESSAGE_PAGE."?is_pool=false&with={$notification->sender_user}")?><!--" class="btn btn-orange-bordered rounded-pill">View</a>-->
<!--                        </div>-->
                    </div>

                <?php elseif($notification->notify_type == 3): ?>
                    <div class="list-thumb">
                        <img src="<?php echo get_avatar_url($notification->sender_user); ?>" alt="">
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">
                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>">
                                    <?php echo get_user_full_name($notification->sender_user); ?>
                                </a>
                                sent you a message
                                <?php if($notification->group_id && $group = CoOwner_Groups::find(array('id'=> $notification->group_id))):
                                    echo "from {$group->name} Pool";
                                endif; ?>
                            </div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
                        <div class="connect-btn">
                            <?php echo substr($notification->message,0,20); echo strlen($notification->message) > 20 ? '...' : null; ?>
                        </div>
                    </div>

                <?php elseif($notification->notify_type == 4): ?>
                    <div class="list-thumb align-items-center justify-content-center d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <g fill="none" fill-rule="evenodd">
                                <g fill="#FFF" fill-rule="nonzero">
                                    <g>
                                        <path d="M40 25.087V20.9c0-.507-.413-.9-.9-.9h-5.02c-.49 0-.9.395-.9.9v.355H28.35C27.455 20.444 26.305 20 25.087 20H20.9c-.49 0-.9.395-.9.9v5.02c0 .508.413.9.9.9h.355v4.829c-.811.897-1.255 2.046-1.255 3.264V39.1c0 .507.413.9.9.9h5.02c.238 0 .47-.096.636-.263.17-.17.264-.396.264-.637v-.355h4.829c.897.811 2.046 1.255 3.264 1.255H39.1c.489 0 .9-.395.9-.9v-5.02c0-.507-.413-.9-.9-.9h-.355V28.35c.811-.897 1.255-2.046 1.255-3.264zm-18.828.561v-4.476h3.915c.985 0 1.912.382 2.61 1.078.107.11.255.177.42.177h5.063v2.594h-5.063c-.196 0-.379.098-.487.26l-.56.84c-.214.321-.523.558-.877.683v-.57c0-.323-.263-.586-.586-.586h-4.435zm5.062 8.16c-.323 0-.586.262-.586.585v4.435h-4.476v-3.915c0-.988.385-1.917 1.083-2.616.11-.11.172-.259.172-.414V26.82h2.594v5.063c0 .196.098.379.26.487l.84.56c.321.214.558.523.683.877h-.57zm.536-1.854l-.577-.385v-3.553c.75-.152 1.418-.594 1.853-1.246l.385-.577h3.553c.152.75.594 1.418 1.246 1.853l.577.385v3.553c-.75.152-1.418.594-1.853 1.246l-.385.577h-3.553c-.152-.75-.594-1.418-1.246-1.853zm12.058 2.398v4.476h-3.915c-.988 0-1.917-.385-2.616-1.083-.11-.11-.259-.172-.414-.172H26.82v-2.594h5.063c.196 0 .379-.098.487-.26l.56-.84c.214-.321.523-.558.877-.683v.57c0 .323.263.586.586.586h4.435zm-1.083-6.65c-.11.11-.172.26-.172.415v5.063h-2.594v-5.063c0-.196-.098-.379-.26-.487l-.84-.56c-.321-.214-.558-.523-.683-.877h.57c.323 0 .586-.263.586-.586v-4.435h4.476v3.915c0 .988-.385 1.917-1.083 2.616z" transform="translate(-860 -303) translate(840 283)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">There is a new property matching your profile.</div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
                        <div class="d-flex align-items-center">
                            <h6 class="d-flex align-items-center pb-2">
                                <div class="pe-3">
                                    <a class="text-orange" href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$notification->notify_id}")?>">
                                        <?php echo get_property_full_address($notification->notify_id); ?>
                                    </a>
                                </div>
                            </h6>
                        </div>
                    </div>

                <?php elseif($notification->notify_type == 5): ?>
                    <div class="list-thumb align-items-center justify-content-center d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <g fill="none" fill-rule="evenodd">
                                <g fill="#FFF" fill-rule="nonzero">
                                    <g>
                                        <path d="M40 25.087V20.9c0-.507-.413-.9-.9-.9h-5.02c-.49 0-.9.395-.9.9v.355H28.35C27.455 20.444 26.305 20 25.087 20H20.9c-.49 0-.9.395-.9.9v5.02c0 .508.413.9.9.9h.355v4.829c-.811.897-1.255 2.046-1.255 3.264V39.1c0 .507.413.9.9.9h5.02c.238 0 .47-.096.636-.263.17-.17.264-.396.264-.637v-.355h4.829c.897.811 2.046 1.255 3.264 1.255H39.1c.489 0 .9-.395.9-.9v-5.02c0-.507-.413-.9-.9-.9h-.355V28.35c.811-.897 1.255-2.046 1.255-3.264zm-18.828.561v-4.476h3.915c.985 0 1.912.382 2.61 1.078.107.11.255.177.42.177h5.063v2.594h-5.063c-.196 0-.379.098-.487.26l-.56.84c-.214.321-.523.558-.877.683v-.57c0-.323-.263-.586-.586-.586h-4.435zm5.062 8.16c-.323 0-.586.262-.586.585v4.435h-4.476v-3.915c0-.988.385-1.917 1.083-2.616.11-.11.172-.259.172-.414V26.82h2.594v5.063c0 .196.098.379.26.487l.84.56c.321.214.558.523.683.877h-.57zm.536-1.854l-.577-.385v-3.553c.75-.152 1.418-.594 1.853-1.246l.385-.577h3.553c.152.75.594 1.418 1.246 1.853l.577.385v3.553c-.75.152-1.418.594-1.853 1.246l-.385.577h-3.553c-.152-.75-.594-1.418-1.246-1.853zm12.058 2.398v4.476h-3.915c-.988 0-1.917-.385-2.616-1.083-.11-.11-.259-.172-.414-.172H26.82v-2.594h5.063c.196 0 .379-.098.487-.26l.56-.84c.214-.321.523-.558.877-.683v.57c0 .323.263.586.586.586h4.435zm-1.083-6.65c-.11.11-.172.26-.172.415v5.063h-2.594v-5.063c0-.196-.098-.379-.26-.487l-.84-.56c-.321-.214-.558-.523-.683-.877h.57c.323 0 .586-.263.586-.586v-4.435h4.476v3.915c0 .988-.385 1.917-1.083 2.616z" transform="translate(-860 -303) translate(840 283)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">There is a new member matching your criteria.</div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="list-thumb small-sub">
                                <img src="<?php echo get_avatar_url($notification->sender_user); ?>" alt="">
                            </div>
                            <div class="side-lst-cnt ps-3">
                                <h6 class="d-flex align-items-center pb-2">
                                    <div class="pe-3">
                                        <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>">
                                            <?php echo get_user_full_name($notification->sender_user); ?>
                                        </a>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>

                <?php elseif($notification->notify_type == 6 || $notification->notify_type == 7): ?>
                    <div class="list-thumb align-items-center justify-content-center d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <g fill="none" fill-rule="evenodd">
                                <g fill="#FFF" fill-rule="nonzero">
                                    <g>
                                        <path d="M40 25.087V20.9c0-.507-.413-.9-.9-.9h-5.02c-.49 0-.9.395-.9.9v.355H28.35C27.455 20.444 26.305 20 25.087 20H20.9c-.49 0-.9.395-.9.9v5.02c0 .508.413.9.9.9h.355v4.829c-.811.897-1.255 2.046-1.255 3.264V39.1c0 .507.413.9.9.9h5.02c.238 0 .47-.096.636-.263.17-.17.264-.396.264-.637v-.355h4.829c.897.811 2.046 1.255 3.264 1.255H39.1c.489 0 .9-.395.9-.9v-5.02c0-.507-.413-.9-.9-.9h-.355V28.35c.811-.897 1.255-2.046 1.255-3.264zm-18.828.561v-4.476h3.915c.985 0 1.912.382 2.61 1.078.107.11.255.177.42.177h5.063v2.594h-5.063c-.196 0-.379.098-.487.26l-.56.84c-.214.321-.523.558-.877.683v-.57c0-.323-.263-.586-.586-.586h-4.435zm5.062 8.16c-.323 0-.586.262-.586.585v4.435h-4.476v-3.915c0-.988.385-1.917 1.083-2.616.11-.11.172-.259.172-.414V26.82h2.594v5.063c0 .196.098.379.26.487l.84.56c.321.214.558.523.683.877h-.57zm.536-1.854l-.577-.385v-3.553c.75-.152 1.418-.594 1.853-1.246l.385-.577h3.553c.152.75.594 1.418 1.246 1.853l.577.385v3.553c-.75.152-1.418.594-1.853 1.246l-.385.577h-3.553c-.152-.75-.594-1.418-1.246-1.853zm12.058 2.398v4.476h-3.915c-.988 0-1.917-.385-2.616-1.083-.11-.11-.259-.172-.414-.172H26.82v-2.594h5.063c.196 0 .379-.098.487-.26l.56-.84c.214-.321.523-.558.877-.683v.57c0 .323.263.586.586.586h4.435zm-1.083-6.65c-.11.11-.172.26-.172.415v5.063h-2.594v-5.063c0-.196-.098-.379-.26-.487l-.84-.56c-.321-.214-.558-.523-.683-.877h.57c.323 0 .586-.263.586-.586v-4.435h4.476v3.915c0 .988-.385 1.917-1.083 2.616z" transform="translate(-860 -303) translate(840 283)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">
                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>"> <?php echo get_user_full_name($notification->sender_user); ?></a>
                                <?php echo $notification->message; ?>
                            </div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
                        <div class="connect-btn">
                            <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>" class="btn btn-orange-bordered rounded-pill">Check profile</a>
                            <?php if($notification->notify_type == 6){ ?>
                                <a href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$notification->notify_id}")?>" class="btn btn-orange-bordered rounded-pill">Check property</a>
                            <?php } ?>
                        </div>
                    </div>

                <?php elseif($notification->notify_type == 8 && !empty($title = get_post_meta($notification->notify_id,'_bbp_topic_id',true))):
                     $topic = get_post($title);
                ?>
                    <div class="list-thumb align-items-center justify-content-center d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <g fill="none" fill-rule="evenodd">
                                <g fill="#FFF" fill-rule="nonzero">
                                    <g>
                                        <path d="M40 25.087V20.9c0-.507-.413-.9-.9-.9h-5.02c-.49 0-.9.395-.9.9v.355H28.35C27.455 20.444 26.305 20 25.087 20H20.9c-.49 0-.9.395-.9.9v5.02c0 .508.413.9.9.9h.355v4.829c-.811.897-1.255 2.046-1.255 3.264V39.1c0 .507.413.9.9.9h5.02c.238 0 .47-.096.636-.263.17-.17.264-.396.264-.637v-.355h4.829c.897.811 2.046 1.255 3.264 1.255H39.1c.489 0 .9-.395.9-.9v-5.02c0-.507-.413-.9-.9-.9h-.355V28.35c.811-.897 1.255-2.046 1.255-3.264zm-18.828.561v-4.476h3.915c.985 0 1.912.382 2.61 1.078.107.11.255.177.42.177h5.063v2.594h-5.063c-.196 0-.379.098-.487.26l-.56.84c-.214.321-.523.558-.877.683v-.57c0-.323-.263-.586-.586-.586h-4.435zm5.062 8.16c-.323 0-.586.262-.586.585v4.435h-4.476v-3.915c0-.988.385-1.917 1.083-2.616.11-.11.172-.259.172-.414V26.82h2.594v5.063c0 .196.098.379.26.487l.84.56c.321.214.558.523.683.877h-.57zm.536-1.854l-.577-.385v-3.553c.75-.152 1.418-.594 1.853-1.246l.385-.577h3.553c.152.75.594 1.418 1.246 1.853l.577.385v3.553c-.75.152-1.418.594-1.853 1.246l-.385.577h-3.553c-.152-.75-.594-1.418-1.246-1.853zm12.058 2.398v4.476h-3.915c-.988 0-1.917-.385-2.616-1.083-.11-.11-.259-.172-.414-.172H26.82v-2.594h5.063c.196 0 .379-.098.487-.26l.56-.84c.214-.321.523-.558.877-.683v.57c0 .323.263.586.586.586h4.435zm-1.083-6.65c-.11.11-.172.26-.172.415v5.063h-2.594v-5.063c0-.196-.098-.379-.26-.487l-.84-.56c-.321-.214-.558-.523-.683-.877h.57c.323 0 .586-.263.586-.586v-4.435h4.476v3.915c0 .988-.385 1.917-1.083 2.616z" transform="translate(-860 -303) translate(840 283)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <div class="pe-3">
                                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>"> <?php echo get_user_full_name($notification->sender_user); ?></a>
                                    replied to your Forum <a href="<?php echo home_url("forum/topic/{$topic->post_name}/#post-".($notification->notify_id-1)); ?>" class="text-orange"><?php echo $topic->post_title; ?></a> thread - <?php echo substr($notification->message,0,10); echo strlen($notification->message) > 10 ? '...' : ''; ?>
                            </div>
                            <?php if($notification->read_at == null): ?>
                                <div class="ms-auto orange-circle"></div>
                            <?php endif; ?>
                        </h6>
                    </div>

                <?php elseif($notification->notify_type == 10): ?>
                    <div class="list-thumb align-items-center justify-content-center d-flex">
                        <?php echo co_owner_get_svg('enable_pool'); ?>
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <?php $group = CoOwner_Groups::find(array('id'=>$notification->notify_id)); ?>
                            <?php if($group): ?>
                                <div class="pe-3">
                                    You have been added to
                                    <a class="text-orange" href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$group->property_id}")?>">
                                        <?php echo $group->name; ?>
                                    </a>
                                    by admin
                                    <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>">
                                        <?php echo get_user_full_name($notification->sender_user); ?>
                                    </a>
                                </div>
                                <?php if($notification->read_at == null): ?>
                                    <div class="ms-auto orange-circle"></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </h6>
                        <div class="connect-btn">
                            <a href="<?php echo home_url(CO_OWNER_MESSAGE_PAGE."/?is_pool=true&with={$notification->notify_id}"); ?>" class="btn btn-orange-bordered rounded-pill">View</a>
                        </div>
                    </div>
                <?php elseif($notification->notify_type == 11): ?>
                    <div class="list-thumb align-items-center justify-content-center d-flex">
                        <?php echo co_owner_get_svg('enable_pool'); ?>
                    </div>
                    <div class="side-lst-cnt ps-3">
                        <h6 class="d-flex align-items-center pb-2">
                            <?php $group = CoOwner_Groups::find(array('id'=>$notification->notify_id)); ?>
                            <?php if($group): ?>
                                <div class="pe-3">
                                    <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE."?id={$notification->sender_user}")?>">
                                        <?php echo get_user_full_name($notification->sender_user); ?>
                                    </a> has left -
                                    <a class="text-orange" href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$group->property_id}")?>">
                                        <?php echo $group->name; ?>
                                    </a>
                                </div>
                                <?php if($notification->read_at == null): ?>
                                    <div class="ms-auto orange-circle"></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </h6>
                        <div class="connect-btn">
                            <a href="<?php echo     home_url(CO_OWNER_MESSAGE_PAGE."/?is_pool=true&with={$notification->notify_id}"); ?>" class="btn btn-orange-bordered rounded-pill">View</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
<?php endif; ?>
