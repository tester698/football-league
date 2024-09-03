import {Component, EventEmitter, Input, SimpleChanges} from '@angular/core';
import {CardModule} from 'primeng/card';
import {FieldsetModule} from 'primeng/fieldset';
import {WeekService} from '../week.service';
import {CommonModule, NgForOf} from "@angular/common";
import {Prediction} from "../week";

@Component({
    selector: 'app-prediction',
    standalone: true,
    imports: [
        CardModule,
        FieldsetModule,
        NgForOf,
        CommonModule,
    ],
    templateUrl: './prediction.component.html',
    styleUrl: './prediction.component.scss'
})
export class PredictionComponent {
    currentWeek!: number;
    predictions!: Prediction[];
    @Input() weekUpdated: EventEmitter<void> = new EventEmitter<void>();
    @Input() gamesReset_1: number = 0;

    minWeek = 4;


    constructor(private weekService: WeekService) {
    }

    ngOnChanges(changes: SimpleChanges) {
        if (changes['gamesReset_1'] ) {
            this.resetState();
        }
    }

    resetState() {
        console.log("reset1" + this.gamesReset_1);
        if (this.gamesReset_1) {
            this.predictions = [];
        }
        this.gamesReset_1 = 0;
    }

    async ngOnInit() {
        this.weekUpdated.subscribe(() => {
            this.updateWeek();
        });
        this.updateWeek();
    }

    async updateWeek() {
        this.currentWeek = await this.weekService.getCurrentWeek();
        if (this.currentWeek < this.minWeek) {
            return//too early to predict
        }
        this.weekService.getPrediction().then((data: Prediction[]) => {
            this.predictions = data;
        });
    }
}
