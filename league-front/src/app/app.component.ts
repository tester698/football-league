import {Component} from '@angular/core';
import {RouterOutlet} from '@angular/router';
import {ResultsComponent} from "./results/results.component";
import {PredictionComponent} from "./prediction/prediction.component";
import {WeekService} from "./week.service";


@Component({
    selector: 'app-root',
    standalone: true,
    imports: [RouterOutlet,
        ResultsComponent,
        PredictionComponent,
    ],
    templateUrl: './app.component.html',
    styleUrl: './app.component.scss'
})
export class AppComponent {
    title = 'Insider Champions League Simulation Project 1.x 🤖';
    currentWeek!: number;
    startWeek = 0;


    constructor(private weekService: WeekService) {
    }

    async ngOnInit() {
        this.currentWeek = await this.weekService.getCurrentWeek();
        if (this.currentWeek == this.startWeek) {
            await this.weekService.playWeek(this.currentWeek + 1)
        }
    }
}
