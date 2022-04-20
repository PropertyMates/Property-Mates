<!--
    is_request
    0 = simple message
    1 = request
    2 = add - remove person
    3 = Say Hello Message
-->
<div class="col-12 chat-message-<?php echo $message->id; ?>" >
    <?php if(isset($old_message_date)):
        $old_date = wp_date('md',strtotime($message->created_at));
        if($old_message_date < $old_date){
            $old_message_date = $old_date;
            ?>
            <div class="bdg-main d-flex justify-content-center message-date" data-date="<?php echo $old_date; ?>">
                <span class="badge bg-grey text-dark">
                    <?php echo wp_date('d M, Y',strtotime($message->created_at)); ?>
                </span>
            </div>
            <?php
        }
        ?>
    <?php endif; ?>

    <?php if($message->is_request == 1): $message_property = get_post($message->property_id); ?>
        <div class="connect-person-main px-3 mt-4 max-w-432px mx-auto">
            <div class="<?php echo get_user_shield_status($message->sender_user) == 1 ? 'mbr-icon-onthumb user-shield-tooltip' : ''; ?>">
                <div class="mbr-thumb mx-auto">
                    <img src="<?php echo get_avatar_url($message->sender_user); ?>" alt="">
                </div>
            </div>
            <h4 class="text-center pt-2">
                <a class="text-orange" href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'?id='.$message->sender_user); ?>">
                    <?php echo get_user_full_name($message->sender_user); ?>
                </a>
                <span class="d-block black-text pt-2">
                    <?php echo $sender == $message->sender_user ? 'You sent a request.' : 'Wants to connect with you'; ?>
                </span>
            </h4>
            <div class="white-bx-main mt-4">
                <?php if($message->property_id > 0): ?>
                    <a class="small-org text-orange" href="<?php echo home_url(CO_OWNER_PROPERTY_DETAILS_PAGE."?id={$message->property_id}");?>">Listing: <?php echo get_property_full_address($message->property_id); ?></a>

                    <table class="table table-sm table-striped border font-size-14px mt-2">
                        <tr>
                           <th colspan="2" class="text-center">
                               Property Information
                           </th>
                        </tr>
                        <tr>
                            <td>Property Market Price</td>
                            <td class="text-end"><?php echo get_pl_property_original_price($message->property_id,true); ?></td>
                        </tr>

                        <?php if(get_pl_interested_in_selling($message->property_id) == 'portion_of_it'): ?>
                            <tr>
                                <td>User wishes to sell</td>
                                <td class="text-end"><?php echo get_pl_i_want_to_sell($message->property_id,true); ?> %</td>
                            </tr>
                            <tr>
                                <td>Selling Price</td>
                                <td class="text-end"><?php echo get_pl_calculated($message->property_id,true); ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if(get_pl_enable_pool($message->property_id)): ?>
                            <?php $property_share = get_property_available_share($message->property_id); ?>
                            <?php $property_price = get_property_available_price($message->property_id); ?>
                            <?php if($property_share > 0): ?>
                                <tr>
                                    <td>Available Portion</td>
                                    <td class="text-end"><?php echo $property_share; ?> %</td>
                                </tr>
                                <tr>
                                    <td>Will Cost</td>
                                    <td class="text-end"><?php echo CO_OWNER_CURRENCY_SYMBOL.' '.number_format($property_price) ; ?></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th colspan="2" class="text-center">
                                        <span class="text-error">Portion of the property are not available</span>
                                    </th>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    </table>

                <?php endif; ?>

                <?php if(!empty($message->interested_in)) : ?>
                    <span class="gry-cnt mt-2">
                        Interested in <?php echo $message->interested_in; ?>% Portion @  <?php echo $message->calculated_price ? CO_OWNER_CURRENCY_SYMBOL.number_format($message->calculated_price) : ''; ?>
                    </span>
                <?php endif; ?>

                <p class="pt-2 mb-0">
                    <p><?php echo $message->message; ?></p>
                    <span class="d-block pt-3">
                        <a href="<?php echo home_url(CO_OWNER_PERSON_DETAILS_PAGE.'?id='.$message->sender_user); ?>">Profile Link</a>
                    </span>
                </p>
            </div>
        </div>
    <?php elseif($message->is_request == 2): ?>
        <?php $content = explode('{{comment}}',$message->message); ?>
        <div class="message ps-3 pe-3">
            <div class="side-lst-cnt w-100 ps-0 text-center">
                <div class="float-end message-time"><?php echo date('h:i a',strtotime($message->created_at)) ?></div><br>
                <p><i><?php echo (get_user_full_name($message->receiver_user)).(isset($content[0]) ? $content[0] : $message->message); ?></i></p>
            </div>
        </div>
        <?php if(isset($content[1]) && !empty($content[1])): ?>
            <div class="message ps-3 pe-3">
                <div class="d-flex">
                    <div class="list-thumb">
                        <img src="<?php echo get_avatar_url($message->sender_user); ?>" alt="">
                    </div>
                    <div class="side-lst-cnt w-100">
                        <h6 class="d-flex"><?php echo get_user_full_name($message->sender_user); ?> <?php echo $sender == $message->sender_user ? '(You)' : ''; ?>
                            <div class="ms-auto message-time"><?php echo date('h:i a',strtotime($message->created_at)) ?></div>
                        </h6>
                        <p><?php echo $content[1]; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php elseif($message->is_request == 3): ?>
        <div class="message ps-3 pe-3 pt-0">
            <div class="d-block">
                <div class="side-lst-cnt w-100">
                    <div class="float-end message-time"><?php echo date('h:i a',strtotime($message->created_at)) ?></div><br>
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="89" height="82" viewBox="0 0 89 82">
                            <g fill="none" fill-rule="evenodd">
                                <g fill-rule="nonzero">
                                    <g>
                                        <g>
                                            <path fill="#EB9C8D" d="M70.558 6.298l-4.257-3.834C64.554.89 62.291.02 59.947.02h-7.56v13.27h.001v.001l-.002-.001h-4.121v21.082h33.928V6.298H70.558z" transform="translate(-848 -296) translate(848 296) translate(1 15)"></path>
                                            <path fill="#D78878" d="M67.47 34.372c.02-.236.035-.473.035-.711 0-1.683-.513-3.296-1.485-4.667L51.882 12.745l-1.15.545h1.656v.001l-.002-.001h-4.12v21.082H67.47z" transform="translate(-848 -296) translate(848 296) translate(1 15)"></path>
                                            <g fill="#FED2A4">
                                                <path d="M50.056 33.321c1.411 2.024.943 4.823-1.059 6.269-.791.57-1.703.845-2.606.845-1.396 0-2.773-.657-3.648-1.888l-.493-.693c1.438 2.028.973 4.846-1.038 6.297-.79.57-1.701.845-2.604.845-1.398 0-2.774-.657-3.65-1.89l-.277-.39c1.44 2.028.978 4.85-1.035 6.302-.79.57-1.701.845-2.604.845-1.398 0-2.775-.657-3.65-1.889L17.053 33.24c-.122-.174-.32-.276-.529-.276l-16.45.028V4.539h12.413l1.35-1.405c1.83-1.9 4.345-2.975 6.974-2.975h14.346l10.858 11.723 12.44 17.744c.566.796.839 1.715.839 2.626 0 1.407-.652 2.794-1.875 3.676-2.012 1.45-4.81.984-6.252-1.044l-1.111-1.563z" transform="translate(-848 -296) translate(848 296) translate(1 15) translate(4.717 1.408)"></path>
                                            </g>
                                            <g>
                                                <path fill="#FDB97E" d="M86.957 1.414V32.55c0 .756-.608 1.369-1.358 1.369h-10.57c-.94 0-1.703-.769-1.703-1.717V1.761c0-.948.762-1.716 1.703-1.716h10.57c.75 0 1.358.613 1.358 1.37z" transform="translate(-848 -296) translate(848 296) translate(1 15) translate(0 3.345)"></path>
                                                <path fill="#FFBD86" d="M34.932 42.926c.876 1.232 2.252 1.889 3.65 1.889.508 0 1.018-.09 1.509-.268-.255.987-.84 1.894-1.728 2.535-.79.57-1.702.845-2.605.845-1.397 0-2.774-.657-3.65-1.89L21.77 31.305c-.122-.175-.32-.277-.53-.277l-16.449.028V2.603h12.412l.184-.191v22.3c0 1.775 1.43 3.212 3.19 3.21l2.506.002c.864 0 1.674.425 2.172 1.137l9.677 13.865z" transform="translate(-848 -296) translate(848 296) translate(1 15) translate(0 3.345)"></path>
                                                <path fill="#ADE194" d="M13.893 1.761v30.44c0 .948-.762 1.717-1.703 1.717H1.06c-.538 0-.973-.44-.973-.98V1.025c0-.542.435-.98.973-.98h11.13c.94 0 1.703.767 1.703 1.715z" transform="translate(-848 -296) translate(848 296) translate(1 15) translate(0 3.345)"></path>
                                                <path fill="#97DA7B" d="M12.19 27.585H1.06c-.538 0-.973-.439-.973-.98v6.332c0 .542.435.98.973.98h11.13c.94 0 1.703-.768 1.703-1.716V25.87c0 .948-.762 1.716-1.703 1.716z" transform="translate(-848 -296) translate(848 296) translate(1 15) translate(0 3.345)"></path>
                                            </g>
                                            <path fill="#FFBD86" d="M50.732 13.29L39.874 1.568h-4.597l-8.655 10.197c-1.155 1.36-1.791 3.093-1.791 4.882 0 2.005.775 3.89 2.182 5.308 1.407 1.418 3.278 2.198 5.268 2.198 1.69 0 3.347-.587 4.664-1.654l7.032-5.688H53.2l-2.468-3.521z" transform="translate(-848 -296) translate(848 296) translate(1 15)"></path>
                                            <path fill="#EB9C8D" d="M52.386.02H43.21c-1.282 0-2.5.565-3.335 1.548L29.275 14.056c-.636.748-.95 1.67-.95 2.591 0 1.023.39 2.044 1.158 2.818 1.428 1.438 3.7 1.563 5.274.289l7.56-6.115c.279-.226.626-.349.984-.349h9.085V.02z" transform="translate(-848 -296) translate(848 296) translate(1 15)"></path>
                                        </g>
                                        <g fill="#000">
                                            <path d="M1.301 20.107h12.02c.215 0 .39.177.39.394v19.422c0 .726.582 1.314 1.3 1.314.72 0 1.302-.588 1.302-1.314v-17.27h1.986c.352 0 .689-.144.934-.4l1.341-1.397c1.563-1.628 3.746-2.562 5.992-2.562h11.422l-8.69 10.26c-1.764 2.082-1.643 5.23.276 7.168 1.017 1.028 2.354 1.55 3.698 1.55 1.153 0 2.312-.385 3.274-1.166l7.578-6.145h6.798l6.933 9.912c.253.362.655.555 1.064.555.259 0 .521-.078.749-.24.587-.42.727-1.24.312-1.832l-6.024-8.613c.352-.235.585-.638.585-1.096 0-.726-.582-1.315-1.301-1.315h-9.574c-.296 0-.584.103-.815.29l-7.934 6.435c-1.047.849-2.553.766-3.503-.193-.964-.974-1.025-2.555-.139-3.602l10.527-12.428c.582-.687 1.428-1.08 2.323-1.08h11.29c.72 0 1.302-.589 1.302-1.315 0-.725-.582-1.314-1.301-1.314H44.125c-1.445 0-2.822.555-3.865 1.54H26.566c-2.946 0-5.81 1.226-7.86 3.362l-.958.998h-1.476c-.227-1.44-1.462-2.546-2.952-2.546H1.301c-.718 0-1.301.588-1.301 1.314 0 .726.583 1.314 1.301 1.314z" transform="translate(-848 -296) translate(848 296)"></path>
                                            <path d="M87.57 51.193H75.722c-.215 0-.39-.177-.39-.395V20.501c0-.217.175-.394.39-.394H87.57c.719 0 1.301-.588 1.301-1.314 0-.726-.582-1.314-1.3-1.314H75.722c-1.607 0-2.92 1.287-2.986 2.895h-.955l-3.858-3.482c-1.908-1.722-4.37-2.703-6.933-2.762-.727-.016-1.314.559-1.33 1.285-.017.725.552 1.327 1.27 1.343 1.945.044 3.812.788 5.259 2.093l4.228 3.817c.239.215.547.334.867.334h1.446v25.314h-6.68c-.1-.99-.453-1.94-1.039-2.766l-1.832-2.62c-.415-.592-1.227-.734-1.814-.315s-.726 1.239-.312 1.831l1.837 2.627c.49.69.684 1.533.547 2.371-.138.839-.59 1.573-1.274 2.068-.685.494-1.519.69-2.349.552-.83-.14-1.557-.596-2.046-1.287l-3.59-5.062c-.417-.59-1.23-.726-1.815-.303-.584.422-.718 1.243-.3 1.833l2.462 3.473c1.011 1.426.685 3.418-.727 4.439-.684.495-1.517.69-2.348.552-.83-.14-1.557-.596-2.047-1.287l-2.462-3.473c-.419-.59-1.232-.726-1.816-.303-.584.422-.718 1.243-.3 1.833l1.97 2.779c1.01 1.426.684 3.417-.728 4.438s-3.383.692-4.395-.734l-1.97-2.779c-.418-.59-1.23-.726-1.815-.304-.584.423-.719 1.244-.3 1.834l1.693 2.39c.49.69.684 1.532.547 2.37-.138.84-.59 1.573-1.274 2.068-1.412 1.021-3.384.692-4.39-.728L23.895 49.146c-.364-.52-.957-.83-1.588-.83h-5.995v-2.793c0-.726-.582-1.314-1.301-1.314s-1.301.588-1.301 1.314v5.275c0 .218-.175.394-.39.394H1.3c-.718 0-1.301.589-1.301 1.314 0 .726.583 1.315 1.301 1.315h12.02c1.6 0 2.909-1.278 2.985-2.877h5.662l10.075 14.392c1.122 1.582 2.89 2.426 4.685 2.426 1.159 0 2.328-.352 3.34-1.084 1.25-.904 2.076-2.244 2.327-3.775.015-.093.028-.187.039-.28.583.194 1.194.294 1.808.294 1.159 0 2.329-.352 3.34-1.084 1.307-.944 2.103-2.344 2.332-3.833.356.136.728.238 1.112.302.317.053.635.08.951.08 1.19 0 2.35-.374 3.336-1.088 1.146-.829 1.899-2.007 2.221-3.288.57.319 1.195.54 1.856.65.318.053.636.08.952.08 1.19 0 2.349-.374 3.336-1.088.948-.685 1.652-1.623 2.046-2.704h7.013c.076 1.599 1.384 2.877 2.986 2.877h11.845c.72 0 1.302-.589 1.302-1.315 0-.725-.582-1.313-1.3-1.313zM44.435 7.24c.719 0 1.301-.587 1.301-1.313V1.362c0-.726-.582-1.314-1.3-1.314-.72 0-1.302.588-1.302 1.314v4.565c0 .726.583 1.314 1.301 1.314zM57.382 9.928c.17.074.346.11.519.11.502 0 .98-.296 1.194-.79l1.804-4.185c.287-.665-.014-1.44-.673-1.73-.659-.289-1.426.015-1.713.68L56.71 8.2c-.287.665.014 1.44.673 1.73zM29.776 9.248c.213.495.691.79 1.193.79.174 0 .35-.036.52-.11.658-.29.96-1.064.672-1.73l-1.804-4.184c-.287-.666-1.054-.97-1.713-.68-.659.29-.96 1.064-.673 1.73l1.805 4.184zM44.435 74.803c-.718 0-1.301.589-1.301 1.314v4.565c0 .726.583 1.314 1.301 1.314.719 0 1.301-.588 1.301-1.314v-4.565c0-.725-.582-1.314-1.3-1.314zM59.095 72.796c-.287-.665-1.054-.97-1.713-.68-.659.29-.96 1.064-.673 1.73l1.804 4.184c.213.495.692.79 1.194.79.173 0 .35-.035.519-.11.659-.29.96-1.064.673-1.729l-1.804-4.185zM31.488 72.116c-.659-.29-1.425.015-1.712.68l-1.805 4.185c-.287.665.014 1.44.673 1.73.17.074.346.11.52.11.501 0 .98-.296 1.193-.79l1.804-4.185c.288-.666-.014-1.44-.673-1.73z" transform="translate(-848 -296) translate(848 296)"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <h5 class="text-center pt-3">You are now connected.</h5>
                    <h4 class="text-center pt-1">Say Hello.</h4>
                </div>
            </div>
        </div>
    <?php elseif($message->is_request == 0): ?>
        <div class="message ps-3 pe-3">
            <div class="d-flex">
                <div class="list-thumb">
                    <img src="<?php echo get_avatar_url($message->sender_user); ?>" alt="">
                </div>
                <div class="side-lst-cnt w-100">
                    <h6 class="d-flex"><?php echo get_user_full_name($message->sender_user); ?> <?php echo $sender == $message->sender_user ? '(You)' : ''; ?>
                        <div class="ms-auto message-time d-flex">
                            <?php echo date('h:i a',strtotime($message->created_at)) ?>
                            <?php if(isset($sender)):
                                $is_reported = CoOwner_Reports::get(CoOwner_Reports::$table,array('user_id'=>$sender,'message_id' => $message->id),true);
                                if(empty($is_reported) && $sender != $message->sender_user): ?>
                                    <div class="message-report-dropdown dropdown ms-3">
                                        <a type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?php echo co_owner_get_svg('3-dots'); ?>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item report-message" href="#" data-id="<?php echo $message->id; ?>">Report</a></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </h6>
                    <p><?php echo $message->message; ?></p>
                </div>
            </div>

            <?php if(isset($files) && count($files) > 0): ?>
                <ul class="files-box">
                <?php foreach ($files as $file): ?>
                    <li class="d-flex align-items-center">
                        <?php if($file->is_link == 0 ): $ext = pathinfo($file->file->url, PATHINFO_EXTENSION); ?>
                            <?php if(in_array($ext,['jpeg','jpg','png'])): ?>
                                <a href="<?php echo $file->file->url?>" class="preview-message-image d-flex align-items-center">
                                    <div class="aco-book-icon">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve"><style type="text/css">.st0{fill:#727272;}</style><g><path class="st0" d="M14.32,0H2.68C2.03,0,1.5,0.53,1.5,1.18V3h-1v1h1v3h-1v1h1v3h-1v1h1v2.82C1.5,15.47,2.03,16,2.68,16h11.65c0.65,0,1.18-0.53,1.18-1.18V1.18C15.5,0.53,14.97,0,14.32,0z M14.5,14.82c0,0.1-0.08,0.18-0.18,0.18H2.68c-0.1,0-0.18-0.08-0.18-0.18V12h1v-1h-1V8h1V7h-1V4h1V3h-1V1.18C2.5,1.08,2.58,1,2.68,1h11.65c0.1,0,0.18,0.08,0.18,0.18V14.82z"></path><rect x="5.5" y="5" class="st0" width="7" height="1"></rect><rect x="5.5" y="8" class="st0" width="5" height="1"></rect></g></svg>
                                    </div>
                                    <div class="aco-tex w-100"><?php echo $file->file->name; ?></div>
                                </a>
                            <?php else: ?>
                                <span class="d-flex align-items-center">
                                    <div class="aco-book-icon">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve"><style type="text/css">.st0{fill:#727272;}</style><g><path class="st0" d="M14.32,0H2.68C2.03,0,1.5,0.53,1.5,1.18V3h-1v1h1v3h-1v1h1v3h-1v1h1v2.82C1.5,15.47,2.03,16,2.68,16h11.65c0.65,0,1.18-0.53,1.18-1.18V1.18C15.5,0.53,14.97,0,14.32,0z M14.5,14.82c0,0.1-0.08,0.18-0.18,0.18H2.68c-0.1,0-0.18-0.08-0.18-0.18V12h1v-1h-1V8h1V7h-1V4h1V3h-1V1.18C2.5,1.08,2.58,1,2.68,1h11.65c0.1,0,0.18,0.08,0.18,0.18V14.82z"></path><rect x="5.5" y="5" class="st0" width="7" height="1"></rect><rect x="5.5" y="8" class="st0" width="5" height="1"></rect></g></svg>
                                    </div>
                                    <div class="aco-tex w-100"><?php echo $file->file->name; ?></div>
                                </span>
                            <?php endif; ?>
                            <a href="<?php echo home_url("?download_file=$file->id") ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g fill="none" fill-rule="evenodd">
                                        <g fill="#F1942A" fill-rule="nonzero">
                                            <g>
                                                <path d="M14.75 9.781v4.344c0 .345-.28.625-.625.625H1.875c-.345 0-.625-.28-.625-.625V9.781H0v4.344C0 15.159.841 16 1.875 16h12.25C15.159 16 16 15.159 16 14.125V9.781h-1.25z" transform="translate(-1393 -709) translate(1393 709)"></path>
                                                <path d="M11 7.366L8.625 9.741 8.625 0 7.375 0 7.375 9.741 5 7.366 4.116 8.25 8 12.134 11.884 8.25z" transform="translate(-1393 -709) translate(1393 709)"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                        <?php else: ?>
                            <div class="d-flex align-items-center">
                                <div class="aco-tex w-100 ps-0"><?php echo $file->file; ?></div>
                                <div class="aco-icon">
                                    <a href="<?php echo $file->file; ?>" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g fill="none" fill-rule="evenodd"><g><g><path d="M0 0H16V16H0z" transform="translate(-1393 -765) translate(1393 765)"></path><path fill="#EB983B" d="M9.5 2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5v5c0 .276-.224.5-.5.5s-.5-.224-.5-.5V2.707l-8.146 8.147c-.196.195-.512.195-.708 0-.195-.196-.195-.512 0-.708L13.293 2H9.5zM14 14v-3.5c0-.276.224-.5.5-.5s.5.224.5.5v4c0 .276-.224.5-.5.5h-13c-.276 0-.5-.224-.5-.5v-13c0-.276.224-.5.5-.5h4c.276 0 .5.224.5.5s-.224.5-.5.5H2v12h12z" transform="translate(-1393 -765) translate(1393 765)"></path></g></g></g></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif ?>
        </div>
    <?php endif; ?>
</div>
