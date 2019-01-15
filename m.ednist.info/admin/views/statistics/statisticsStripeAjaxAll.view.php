  <div class="some-user" data-userId="0">
                    <table>
                        <tr>
                            <td class="user_name">Всего:</td>
                            <td class="news_count"><?= $statistics_all['countNews'] ?></td>
                            <td class="news_visits">
                                <?
                                if (isset($statistics_all['countVisits'])) {
                                    echo $statistics_all['countVisits'];
                                } else {
                                    echo 0;
                                }
                                ?>

                            </td>
                            <td class="news_efficiency">
                                <?
                                if (isset($statistics_all['countVisits'])) {
                                    echo round(($statistics_all['countVisits'] / $statistics_all['countNews']),2);
                                } else {
                                    echo 0;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>

                </div>