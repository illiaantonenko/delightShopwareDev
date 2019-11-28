{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_before_page"}
    <span id="callback-btn" onclick="showCallbackModel()">&#9743;</span>
    <div id="callback-modal" class="modal">
        <div class="modal-body">
            <div class="modal-header">
                Leave your contacts and we'll call you
                <span class="close" onclick="hideCallbackModal()">&times;</span>
            </div>
            <!-- Modal content -->
            <div class="modal-content">
                <form action="delight-frontend-form" method="post" id="callback-form">
                    <label>
                        Name
                        <input class="field" type="text" name="name">
                    </label>
                    <label>
                        Phone number
                        <input class="field" type="tel" id="phone" name="phone" placeholder="+3809999999">
                    </label>
                    <button type="submit" onclick="sendCallbackForm(event)">Send</button>
                </form>
                <div id="message" class="callback-message">
                    <div class="modal-header">
                        <span class="close" onclick="hideCallbackModal()">&times;</span>
                    </div>
                    <div class="modal-content"></div>
                </div>
            </div>
        </div>
    </div>
{/block}