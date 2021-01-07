<?php

namespace App\Http\Controllers;

use App\Charts\TicketsStatusChart;
use App\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    //
    public function index()
    {


        $ticketStatus = DB::table('tickets')
            ->select('id', DB::raw('count(id) as ticket_count'))
            ->groupBy('status')
            ->pluck('ticket_count')->all();
        $ticketStatusLabel = DB::table('tickets')
            ->groupBy('status')
            ->pluck('status')->all();

        $statusChart = new TicketsStatusChart();

        $statusChart->labels($ticketStatusLabel);
        $statusChart->dataset('Ticket Status', 'bar', $ticketStatus);


        $ticketCountArray=array();
        $ticketDepartments = DB::select('SELECT count(id) as ticket_count,(select name from departments where id in(tickets.department_id)) as dept FROM `tickets`  GROUP by department_id');
        foreach ($ticketDepartments as $abc) {
            array_push($ticketCountArray,$abc->ticket_count);
        }

        $ticketDepartmentsLabelsArray=array();
        $ticketDepartmentsLabels = DB:: select('SELECT (select name from departments where id in(tickets.department_id) ) as dept FROM `tickets`  GROUP by department_id');
        foreach ($ticketDepartmentsLabels as $abc) {
            array_push($ticketDepartmentsLabelsArray,$abc->dept);
        }

        $departmentChart = new TicketsStatusChart();

        $departmentChart->labels($ticketDepartmentsLabelsArray);
        $departmentChart->dataset('Ticket Count ', 'pie', $ticketCountArray);


        return view('admin.reports', compact('statusChart', 'departmentChart'));
    }
}
