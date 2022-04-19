import React, { PureComponent } from 'react';
import moment from 'moment';
import LangHelper from '@jsroot/common/langHelper';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MatchStatus } from '@jsroot/common/constants';

export default class MatchStage extends PureComponent {
    constructor(props) {
        super(props);

        this.intervalId = null;

        this.state = {
            minutes: `0'`
        };
    }

    componentWillUnmount() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    getMatchStage = () => {
        const match = this.props.match;
        let stage = null;
        switch (match.status) {
            case MatchStatus.NotStarted:
                const startTime = DateTimeService.convertFromUTC(match.startTime);
                stage = <span className="font-weight-bold">{DateTimeService.toShortTimeFormat(startTime)}</span>;
                break;

            case MatchStatus.FirstHalf:
                this.intervalId = setInterval(() => this.setState({ minutes: this.getFirstHalfTimeMinutes(match) }), 1000 * 60);
                stage = <span>{this.getFirstHalfTimeMinutes(match)}</span>;
                break;

            case MatchStatus.SecondHalf:
                this.intervalId = setInterval(() => this.setState({ minutes: this.getSecondHalfTimeMinutes(match) }), 1000 * 60);
                stage = <span>{this.getSecondHalfTimeMinutes(match)}</span>;
                break;

            case MatchStatus.ExtraTime:
                this.intervalId = setInterval(() => this.setState({ minutes: this.getExtraTimeMinutes(match) }), 1000 * 60);
                stage = <span>{this.getExtraTimeMinutes(match)}</span>;
                break;

            case MatchStatus.Intermission:
                stage = <p>{LangHelper.getResource('Intermission')}</p>;
                break;

            case MatchStatus.PenaltyShootout:
                stage = <span>{LangHelper.getResource('Penalty')}</span>;
                break;

            case MatchStatus.Delay:
                stage = <span>{LangHelper.getResource('Delay')}</span>;
                break;

            case MatchStatus.Postpone:
                stage = <span>{LangHelper.getResource('Postponed')}</span>;
                break;

            case MatchStatus.TBD:
                stage = <span>{LangHelper.getResource('ToBeDecided')}</span>;
                break;

            default:
                stage = <span>{LangHelper.getResource('Finish')}</span>;
                break;
        }

        return stage;
    };

    getFirstHalfTimeMinutes = match => {
        const startTime = match.footballStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime));
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const minutes = currentTime.diff(startTime, 'minutes');
        return minutes <= 45 ? `${minutes}'` : '45+';
    };

    getSecondHalfTimeMinutes = match => {
        const startTime = match.footballNextHalfStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballNextHalfStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime)).add(1, 'hours');
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const minutes = currentTime.diff(startTime, 'minutes');
        return minutes <= 45 ? `${45 + minutes}'` : '90+';
    };

    getExtraTimeMinutes = match => {
        const startTime = match.footballAddStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballAddStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime)).add(2, 'hours');
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const minutes = currentTime.diff(startTime, 'minutes');
        return `${90 + minutes}'`;
    };

    render() {
        return (
            <div className="soccer-match-stage">{this.getMatchStage()}</div>
        );
    }
}