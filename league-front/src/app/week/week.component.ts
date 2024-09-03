import {Component, Input, EventEmitter} from '@angular/core';
import {WeekService} from "../week.service";
import {Game} from "../week";
import {CommonModule} from '@angular/common';
@Component({
    selector: 'app-week',
    standalone: true,
    imports: [
        CommonModule,
    ],
    templateUrl: './week.component.html',
    styleUrl: './week.component.scss'
})
export class WeekComponent {
    week!: Game[];
    currentWeek!: number;
    @Input() weekUpdated: EventEmitter<void> = new EventEmitter<void>();


    constructor(private weekService: WeekService) {
    }

    async ngOnInit() {
        this.weekUpdated.subscribe(() => {
            this.updateWeek();
        });
        this.updateWeek();
    }

    async updateWeek() {
        this.currentWeek = await this.weekService.getCurrentWeek();
        this.weekService.getWeek(this.currentWeek).then((data: Game[]) => {
            this.week = data;
        });
    }
}
