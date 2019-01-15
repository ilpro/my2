<div class="row without-hover">

    <div class="pull-left bold">

        <span>Издания</span>

    </div>

    <div class="list-site" id="countSlideLinks">

    </div>

    <div class="site-list">

        <span class="control" id="leftControl"><div class="site-list-l "></div></span>

        <!--span  id="leftControl2"><div class="site-list-l "></div></span-->


        <span class="control" id="rightControl"><div class="site-list-r"></div></span>

        <!--span  id="rightControl2"><div class="site-list-r"></div></span-->

    </div>

    </br>

</div>

<div id="slideshow">

    <div id="slidesContainer">

        <div class="slide" style="width:640px;">

            <ul id="items">

                <? if (is_array($sources))

                    foreach ($sources as $source):?>

                        <li>

                            <div class="checkLight">

                                <input type="checkbox" id="c<?= $source['sourceId'] ?>" name="newsLinks[]"
                                       value="<?= $source['sourceId'] ?>"/>

                                <label for="c<?= $source['sourceId'] ?>"><span></span>

                                    <p><?= $source['sourceLink'] ?></p></label>

                            </div>

                        </li>

                    <? endforeach; ?>

            </ul>

        </div>

    </div>

</div>