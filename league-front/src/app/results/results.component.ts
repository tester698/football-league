import {ChangeDetectorRef, Component, EventEmitter, Output} from '@angular/core';
import {Team} from '../team';
import {TeamService} from '../team.service';
import {TableModule} from 'primeng/table';
import {CommonModule} from '@angular/common';
import {ButtonModule} from "primeng/button";
import {WeekComponent} from "../week/week.component";
import {WeekService} from "../week.service";
import {PredictionComponent} from "../prediction/prediction.component";
import {MassResultsComponent} from "../mass-results/mass-results.component";
import {Game} from "../week";

@Component({
    selector: 'app-results',
    standalone: true,
    imports: [TableModule, CommonModule, ButtonModule, WeekComponent, PredictionComponent, MassResultsComponent,],
    providers: [TeamService],
    templateUrl: './results.component.html',
    styleUrl: './results.component.scss'
})


export class ResultsComponent {
    teams!: Team[];
    gamesMass: Game[] = [];
    gamesReset_1 = 0;
    gamesReset_2 = 0;

    @Output() weekUpdated = new EventEmitter<void>();

    constructor(
        private teamService: TeamService,
        private weekService: WeekService,
        private changeDetectorRef: ChangeDetectorRef) {
    }

    async ngOnInit() {
        await this.weekService.getCurrentWeek();
        await this.teamService.getTeamResults().then((data) => {
            this.teams = data;
        });
    }

    async playWeek() {
        const currentWeek = await this.weekService.getCurrentWeek()
        this.weekService.playWeek(currentWeek + 1).then((data) => {
            this.teamService.getTeamResults().then((data) => {
                this.teams = data;
                this.changeDetectorRef.detectChanges();
                this.weekUpdated.emit();
            });
        });
    }

    async resetChampionship() {
        await this.weekService.resetChampionship();
        const currentWeek = await this.weekService.getCurrentWeek()
        this.weekService.playWeek(currentWeek + 1).then((data) => {
            this.teamService.getTeamResults().then((data) => {
                this.teams = data;
                this.weekUpdated.emit();
                if (this.gamesReset_1 ==1) {
                    this.gamesReset_1 = 0;
                    this.changeDetectorRef.detectChanges();
                }
                this.gamesReset_1 = 1;
                if (this.gamesReset_2 ==1) {
                    this.gamesReset_2 = 0;
                    this.changeDetectorRef.detectChanges();
                }
                this.gamesReset_2 = 1;
                this.changeDetectorRef.detectChanges();
            });
        });
    }

    async playAllWeeks() {
        const maxWeek = await this.weekService.getMaxWeek();
        const currentWeek = await this.weekService.getCurrentWeek();
        for (let i = currentWeek; i <= maxWeek; i++) {
            this.playWeek();
            this.gamesMass = await this.weekService.getWeek(i);
            this.changeDetectorRef.detectChanges();
            await this.sleep(1000);
        }
    }

    sleep(ms: number) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

