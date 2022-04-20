<?php if(isset($file_attachment) && $file_attachment != null): ?>
    <li>
        <?php if($file_attachment->is_link == 0 ): $ext = pathinfo($file_attachment->file->url, PATHINFO_EXTENSION); ?>
            <div class="d-flex align-items-center">
                <?php if(in_array($ext,['jpeg','jpg','png'])): ?>
                    <a href="<?php echo $file_attachment->file->url?>" class="preview-message-image d-flex align-items-center">
                        <div class="aco-book-icon">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve"><style type="text/css">.st0{fill:#727272;}</style><g><path class="st0" d="M14.32,0H2.68C2.03,0,1.5,0.53,1.5,1.18V3h-1v1h1v3h-1v1h1v3h-1v1h1v2.82C1.5,15.47,2.03,16,2.68,16h11.65c0.65,0,1.18-0.53,1.18-1.18V1.18C15.5,0.53,14.97,0,14.32,0z M14.5,14.82c0,0.1-0.08,0.18-0.18,0.18H2.68c-0.1,0-0.18-0.08-0.18-0.18V12h1v-1h-1V8h1V7h-1V4h1V3h-1V1.18C2.5,1.08,2.58,1,2.68,1h11.65c0.1,0,0.18,0.08,0.18,0.18V14.82z"></path><rect x="5.5" y="5" class="st0" width="7" height="1"></rect><rect x="5.5" y="8" class="st0" width="5" height="1"></rect></g></svg>
                        </div>
                        <div class="aco-tex w-100"><?php echo $file_attachment->file->name; ?></div>
                    </a>
                <?php else: ?>
                    <div class="aco-book-icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve"><style type="text/css">.st0{fill:#727272;}</style><g><path class="st0" d="M14.32,0H2.68C2.03,0,1.5,0.53,1.5,1.18V3h-1v1h1v3h-1v1h1v3h-1v1h1v2.82C1.5,15.47,2.03,16,2.68,16h11.65c0.65,0,1.18-0.53,1.18-1.18V1.18C15.5,0.53,14.97,0,14.32,0z M14.5,14.82c0,0.1-0.08,0.18-0.18,0.18H2.68c-0.1,0-0.18-0.08-0.18-0.18V12h1v-1h-1V8h1V7h-1V4h1V3h-1V1.18C2.5,1.08,2.58,1,2.68,1h11.65c0.1,0,0.18,0.08,0.18,0.18V14.82z"></path><rect x="5.5" y="5" class="st0" width="7" height="1"></rect><rect x="5.5" y="8" class="st0" width="5" height="1"></rect></g></svg>
                    </div>
                    <div class="aco-tex w-100"><?php echo $file_attachment->file->name; ?></div>
                <?php endif; ?>
                <div class="aco-icon">
                    <a href="<?php echo home_url("?download_file=$file_attachment->id") ?>">
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
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex align-items-center">
                <div class="aco-tex w-100 ps-0"><?php echo $file_attachment->file; ?></div>
                <div class="aco-icon">
                    <a href="<?php echo $file_attachment->file; ?>" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g fill="none" fill-rule="evenodd">
                                <g>
                                    <g>
                                        <path d="M0 0H16V16H0z" transform="translate(-1393 -765) translate(1393 765)"></path>
                                        <path fill="#EB983B" d="M9.5 2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5v5c0 .276-.224.5-.5.5s-.5-.224-.5-.5V2.707l-8.146 8.147c-.196.195-.512.195-.708 0-.195-.196-.195-.512 0-.708L13.293 2H9.5zM14 14v-3.5c0-.276.224-.5.5-.5s.5.224.5.5v4c0 .276-.224.5-.5.5h-13c-.276 0-.5-.224-.5-.5v-13c0-.276.224-.5.5-.5h4c.276 0 .5.224.5.5s-.224.5-.5.5H2v12h12z" transform="translate(-1393 -765) translate(1393 765)"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </li>
<?php endif; ?>
