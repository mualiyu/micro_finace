<?php

namespace App\Http\Controllers;

use App\Models\Ebulksms;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Nette\Utils\Random;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('transfer.index');
    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => ['required'],
            'ben' => ['required'],
            'amount' => ['required'],
            'nar' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $cur_user = User::where('account', '=', $request->source)->get();
        if (count($cur_user) > 0) {
            if ($cur_user[0]->amount >= $request->amount) {
                $a_new = (int)$cur_user[0]->amount - (int)$request->amount;
                User::where('id', '=', $cur_user[0]->id)->update([
                    'amount' => $a_new,
                ]);

                // add the money to receiver
                $rec_user = User::where('account', '=', $request->ben)->get();

                if (count($rec_user) > 0) {
                    # code...
                    $a_r_new = $rec_user[0]->amount + $request->amount;
                    $update_rec = User::where('id', '=', $rec_user[0]->id)->update([
                        'amount' => $a_r_new,
                    ]);

                    if ($update_rec) {
                        $transaction = Transaction::create([
                            'type' => 'Money Transfer',
                            'status' => 'Approved',
                            'narration' => $request->nar,
                            'amount' => $request->amount,
                            'from' => $cur_user[0]->account,
                            'to' => $rec_user[0]->account,
                        ]);

                        if ($transaction) {

                            // SMS Debit Alert to the Sender
                            $ebulk = new Ebulksms();
                            $from = "MicroFinance Bank";
                            $msg = "Debit! \nAcct:" . $cur_user[0]->account . " \nAmt:N-" . $request->amount . " \nDesc: TRF TO " . $rec_user[0]->name . " \nDate:" . now() . " \nBal:N" . $cur_user[0]->amount . " \nGoto to APP " . url('/') . " ";
                            $ss = strval($msg);
                            $new = substr($cur_user[0]->phone, -10);
                            $num = '234' . $new;
                            $to = $num;

                            try {
                                $ebulk->useJSON($from, $ss, $to);
                            } catch (Exception $e) {
                                // return back()->with('error', 'Oops Phone cannot be verified, Check your Connection and Phone and then try again.');
                            }

                            // SMS Credit alert to receiver
                            $ebulk_k = new Ebulksms();
                            $from = "MicroFinance Bank";
                            $msg = "Credit! \nAcct:" . $rec_user[0]->account . " \nAmt:N" . $request->amount . " \nDesc: TRF By " . $cur_user[0]->name . " \nDate:" . now() . " \nBal:N" . $rec_user[0]->amount . " \nGoto to APP " . url('/') . " ";
                            $ss = strval($msg);
                            $new = substr($cur_user[0]->phone, -10);
                            $num = '234' . $new;
                            $to = $num;

                            try {
                                $ebulk_k->useJSON($from, $ss, $to);
                            } catch (Exception $e) {
                                // return back()->with('error', 'Oops Phone cannot be verified, Check your Connection and Phone and then try again.');
                            }

                            return back()->with('success', 'Successful, The sum of ' . $request->amount . ' Naira has been transferred to ' . $rec_user[0]->name . '(' . $rec_user[0]->account . ') ');
                        }
                    }
                }
            } else {
                return back()->with('error', 'Error, Insufficient Fund ' . $request->amount . ' found in your account, try again.')->withInput();
                // return back()->with('error', 'Error, Transfer of ' . $request->amount . ' Failed, try again.');
            }
        }
        // return $request->all();
    }

    public function get_ben_data(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->ben;

            $user = User::where('account', '=', $data)->get();

            $data = $user[0];

            return $data;
        }
    }
}
