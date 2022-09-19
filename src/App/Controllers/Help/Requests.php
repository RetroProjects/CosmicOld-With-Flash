<?php
namespace Cosmic\App\Controllers\Help;

use Cosmic\App\Config;
use Cosmic\App\Helpers\Helper;


use Cosmic\App\Models\Help;
use Cosmic\App\Models\Player;
use Cosmic\App\Middleware\BannedMiddleware;

use Cosmic\System\LocaleService;
use Cosmic\System\ValidationService;
use Cosmic\System\ViewService;

use stdClass;

class Requests
{ 
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function ticket($ticket)
    {
        $ticket = Help::getRequestById($ticket, request()->player->id);
        if ($ticket == null) {
            (redirect('/help'));
        }

        $ticket->author = Player::getDataById($ticket->player_id, array('username','look'));
        $ticket->ctickets = Help::countTicketsByUserId($ticket->player_id);
        $ticket->latest = Help::latestHelpTicketReaction($ticket->id);
        $ticket->message = Helper::bbCode($ticket->message);

        $reactions = Help::getTicketReactions($ticket->id);

        foreach ($reactions as $reaction) {
            $reaction->author = Player::getDataById($reaction->practitioner_id, array('username','look'));
            $reaction->message = Helper::bbCode($reaction->message);
        }

        $this->data->requests = $ticket;
        $this->data->requests->reactions = $reactions;

        $this->index();
    }

    public function createpost()
    {
        ValidationService::validate([
            'message'   =>   'required|max:2000',
            'ticketid'  =>   'required|numeric'
        ]);

        $ticket = Help::getRequestById(input('ticketid'), request()->player->id);
        if ($ticket == null || $ticket->player_id != request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }

        $latest_post = Help::latestHelpTicketReaction($ticket->id);
        if ($latest_post ? $latest_post->practitioner_id == request()->player->id : true) {
            response()->json(["status" => "success", "message" => LocaleService::get('help/no_answer_yet')]);
        }

        Help::addTicketReaction($ticket->id, request()->player->id, Helper::filterString(input('message')));
        Help::updateTicketStatus($ticket->id, 'wait_reply');

        response()->json(["status" => "success", "message" => LocaleService::get('core/notification/message_placed'), "replacepage" => "help/requests/" . $ticket->id . "/view"]);
    }

    public function create()
    {
        ValidationService::validate([
            'subject'   =>   'required|min:1|max:100',
            'message'   =>   'required|min:1|max:2000'
        ]);

        if (in_array('open', array_column(Help::getTicketsByUserId(request()->player->id), 'status'))) {
            response()->json(["status" => "error", "message" => LocaleService::get('help/already_open')]);
        }

        $this->data->subject = input('subject');
        $this->data->message = Helper::filterString(input('message'));

        Help::createTicket($this->data, request()->player->id, getIpAddress());
        response()->json(["status" => "success", "message" => LocaleService::get('help/ticket_created'), "replacepage" => "help/requests/view"]);
    }

    public function index()
    {
        $banned_reason = BannedMiddleware::$ban;
      
        if(request()->player) {
            $tickets = Help::getTicketsByUserId(request()->player->id);
            $this->data->tickets = $tickets;
        }

        ViewService::renderTemplate('Help/requests.html', [
            'title' => LocaleService::get('core/title/help/requests'),
            'page'  => 'help',
            'data'  => $this->data,
            'banned_reason' => $banned_reason
        ]);
    }
}
