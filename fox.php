<?php
             $ch = curl_init("URL");

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch,CURLOPT_HEADER,"Accept:application/vnd.cleveron+json; version=1.0");
            curl_setopt($ch,CURLOPT_HEADER,"Content-Type:application/vnd.cleveron+json");
            curl_setopt($ch,CURLOPT_USERPWD,"USERNAME:PASSWORD");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            $res = json_decode($res);
            curl_close($ch);
            print_r($res);