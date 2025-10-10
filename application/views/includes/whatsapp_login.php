<div class="modal fade" id="whatsapp-login" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="max-width:80%;">
        <div class="modal-content animated slideDown">
            <div class="modal-header">
                <h4 class="modal-title">Whatsapp Login</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <input type="hidden" id="requestID" value="">
                            <img id="loginQRImage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEAAQMAAABmvDolAAAABlBMVEX///8AAABVwtN+AAADY0lEQVR42uyYMY6sPBCEyyJw6Bvgi4zgWgQj2dIEXMsjLmJu4NABon5185jdfdEfvGEdDBFiv8Dr6e6qanyez/Ovn5Hksjt0ZJkAWCYA3UpybwYYALvsrnbPjJAAG+ERuBb900WA55wMa8dc7OaLjYN3dVwLt7aAZS0VhrDMsI8E1pu5GkjQm3QzWfh4ZlY0BgA2GSI8iZAMuQ1eXn7Uw5sBLfu11Pvg3fzt5Wdf/DIA7cTCOOZikyl2G+Re848h8mZg8CVsvqBjdiRZ7/rZFPvYmwFMZhxZapdkbuyO25gxwRfL6wC6eYOzD/J1dQhksRHNAGNm7dZi45iVZBxAph7cLgRgEyDXyVmG/B1ws0yzhgCgTPBEl7yrt2PsI2xwVQTqGkCG/LISYQemcWXtEjDJtGc7ADylthgNGWFouftiU4/a7VcBRirNs94HuDn1TseCTXB8pGYAnat6k/6r9uKY8WeaXQTMmyni8UrgjhpEo8/TNgLAAzeRbKAAcFqE083QPtJVgLqFHrgPnlF780k5rTvnQwuAHt6w3sdcJvSwm4iUyGIcLgO8KrK0JKy+POmqOJvzkG0AsdudiGBRNxilN7qMCwGTOS+kCnFY1oKQPCU+fA2x3wf0v5C7fGYJOxI0Mrn89W++Fxg8SR1Z+rMWyrSfOh7S0whgpMDy4Xbm1GtqZu2yOzvzCuD4Tj4p1pg6P8OyO3tqdwPAAMi98bHjkGxR84oe6ParAA3LVGkWty5C40vQRhhaAnDzKouujjuoHn5c+TIY7wdGOlLiZ5Kx3ztuhsUuO+TYrQCDuGVfJOOUwOw0sYokverh/YAcSaKNfKe4vqALj+xOo9UCMHgXb72zcoHTTXJ+Ojw8QroKgKemqqge5tgu8vDJaAaQvkDvRA3dnM49Q/I6Uq4CJPr10LAemCHDlPN27mMbAagtWaUnRLIr1O307ltfvBsYqRndRsOX6zukObQD6FZNJInUPaQEMV06vGry/cC52NccodtFiD1ey9+b/98EdPOv3u/Yrv/xqBl8XAfId5n2zyylL6lBG5RfitMGoHsYskxjhtVd+q13r5u8CNhdldSgC704gPEm9q8hALCSas4hJonezd9j9fsBLfvs9CYxrvKz0lXg2xD7feDzfJ7///wXAAD//1eN+WXnRzWEAAAAAElFTkSuQmCC" />  <br>
                            <label for="">QR Code Scan</label>
                        </div>

                        <div class="col-md-12" align="center">
                            <div style="display: inline-block;" id="timeoutText">Timeout</div> in <div id="counter"  style="display: inline-block;">30</div> Second(s)                            
                        </div>

                        <div class="col-md-12" align="center">
                            <div class="error wpLoginError"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location.reload();"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>