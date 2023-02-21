<?php

return array(
    //============================== New strings to translate ==============================//
    // Defined in file C:\\wamp\\www\\attendize\\resources\\views\\ManageOrganiser\\Events.blade.php
    'sort'                                      =>
        array(
            'event_title'   => 'イベントタイトル',
            'start_date'    => '開始日',
            'created_at'    => '作成日',
            'quantity_sold' => '販売数量',
            'sales_volume'  => '販売量',
            'sort_order'    => 'カスタムソート順',
            'title'         => 'チケットのタイトル',
        ),
    // Defined in file C:\\wamp\\www\\attendize\\resources\\views\\ManageOrganiser\\Events.blade.php
    //==================================== Translations ====================================//
    'account_successfully_updated'              => 'アカウントは正常に更新されました',
    'addInviteError'                            => '出席者を招待する前にチケットを作成する必要があります。',
    'attendee_already_cancelled'                => '出席者はすでにキャンセルされています',
    'attendee_already_checked_in'               => '出席者はすでに時刻にチェックインしています',
    'attendee_exception'                        => 'この出席者の招待中にエラーが発生しました。やり直してください。',
    'attendee_successfully_checked_in'          => '出席者のチェックインに成功しました',
    'attendee_successfully_checked_out'         => '出席者は正常にチェックアウトしました',
    'attendee_successfully_invited'             => '出席者が招待しました！',
    'cant_delete_ticket_when_sold'              => 'すみません、すでに販売済みのため、このチケットを削除できません。',
    'check_in_all_tickets'                      => 'この注文に関連するすべてのチケットをチェックイン',
    'confirmation_malformed'                    => '確認コードが見つからないか,形式が正しくありません。',
    'confirmation_successful'                   => '成功しました。あなたのEメールは確認されました。ログインできます。',
    'error'                                     =>
        array(
            'email'                =>
                array(
                    'email'    => '有効なメールアドレスを入力してください。',
                    'required' => 'メールアドレスは必須です。',
                    'unique'   => '既に使用されているメールアドレスです。',
                ),
            'first_name'           =>
                array(
                    'required' => '名を入力してください。',
                ),
            'last_name'            =>
                array(
                    'required' => '姓を入力してください。',
                ),
            'page_bg_color'        =>
                array(
                    'required' => '背景色を入力してください。',
                ),
            'page_header_bg_color' =>
                array(
                    'required' => 'ヘッダーの背景色を入力してください。',
                ),
            'password'             =>
                array(
                    'passcheck' => 'このパスワードは正しくありません。',
                ),
        ),
    'event_create_exception'                    => 'おっと！ 予定の作成中に問題が発生しました。もう一度お試しください',
    'event_page_successfully_updated'           => 'イベントページは正常に更新されました。',
    'event_successfully_updated'                => 'イベントが更新されました！',
    'fill_email_and_password'                   => 'あなたのメールアドレスとパスワードを入力してください',
    'image_upload_error'                        => '画像のアップロード中に問題が発生しました。',
    'invalid_ticket_error'                      => '無効なチケットです。もう一度やり直してください。',
    'login_password_incorrect'                  => 'あなたのユーザー名/パスワードの組み合わせが間違っていました',
    'maximum_refund_amount'                     => 'あなたが払い戻すことができる最高額は:moneyです。',
    'message_successfully_sent'                 => 'メッセージが正常に送信されました。',
    'new_order_received'                        => 'イベントで新しい注文を受け取りました :event [:order]',
    'no_organiser_name_error'                   => 'イベント主催者の名前を入力する必要があります。',
    'nothing_to_do'                             => '何もしない',
    'nothing_to_refund'                         => '払い戻しはありません。',
    'num_attendees_checked_in'                  => ':num出席者チェックイン。',
    'order_already_refunded'                    => '注文は既に払い戻されました！',
    'order_cant_be_refunded'                    => '注文は返金できません。',
    'order_page_successfully_updated'           => '注文ページは正常に更新されました。',
    'order_payment_status_successfully_updated' => '注文の支払いステータスが更新されました',
    'organiser_design_successfully_updated'     => '主催者デザインを更新しました！',
    'organiser_other_error'                     => '主催者を見つける際に問題がありました。',
    'password_successfully_reset'               => 'パスワードがリセットされました！',
    'payment_information_successfully_updated'  => '支払い情報が更新されました',
    'please_enter_a_background_color'           => '背景色を入力してください。',
    'quantity_min_error'                        => '利用可能な数量は、販売または予約された金額を下回ることはできません。',
    'refreshing'                                => '更新中...',
    'refund_exception'                          => '払い戻しの処理中に問題が発生しました。情報を確認してもう一度やり直してください。',
    'refund_only_numbers_error'                 => 'このフィールドに入力できるのは数字のみです。',
    'social_settings_successfully_updated'      => 'ソーシャル設定は正常に更新されました。',
    'stripe_error'                              => 'あなたのStripeアカウントへの接続中にエラーが発生しました。やり直してください。',
    'stripe_success'                            => 'あなたのStripeアカウントに正常に接続しました。',
    'success_name_has_received_instruction'     => '成功しました。<b>:name</b>に詳しい説明が送信されました。',
    'successfully_cancelled_attendee'           => '出席者のキャンセルに成功しました！',
    'successfully_cancelled_attendees'          => '出席者のキャンセルに成功しました！',
    'successfully_created_organiser'            => '主催者の作成に成功しました。',
    'successfully_created_question'             => '問題なく作成された質問',
    'successfully_deleted_question'             => '問題なく削除された質問',
    'successfully_edited_question'              => '問題なく編集された質問',
    'successfully_refunded_and_cancelled'       => '注文の払い戻しに成功し,出席者をキャンセルしました！',
    'successfully_refunded_order'               => '正常に払い戻された注文！',
    'successfully_saved_details'                => '正常に保存された詳細！',
    'successfully_updated_attendee'             => '出席者の更新に成功しました！',
    'successfully_updated_organiser'            => '開催者の更新に成功しました！',
    'successfully_updated_question'             => '問題なく更新された質問',
    'successfully_updated_question_order'       => '質問の順番が更新されました',
    'survey_answers'                            => 'アンケート回答',
    'the_order_has_been_updated'                => '注文が更新されました',
    'this_question_cant_be_deleted'             => 'この質問は削除できません',
    'ticket_field_required_error'               => 'チケットフィールドは必須です。',
    'ticket_not_exists_error'                   => '選択したチケットは存在しません',
    'ticket_order_successfully_updated'         => 'チケット注文は正常に更新されました',
    'ticket_successfully_deleted'               => 'チケットは正常に削除されました',
    'ticket_successfully_resent'                => 'チケットは正常に再送信されました。',
    'ticket_successfully_updated'               => 'チケットは正常に更新されました。',
    'tickets_for_event'                         => 'イベントのチケット：:event',
    'whoops'                                    => 'おっと！ 問題が発生したようです。やり直してください。',
    'your_password_reset_link'                  => 'あなたのパスワードリセットリンク',
);
