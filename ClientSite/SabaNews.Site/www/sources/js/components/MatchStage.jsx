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
            stage: '',
            className: ''
        };
    }

    componentDidMount() {
        this.getMatchStage();
    }

    componentWillUnmount() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
    }

    componentDidUpdate(prevProps) {
        if (prevProps.match !== this.props.match) {
            if (this.intervalId) {
                clearInterval(this.intervalId);
            }

            this.getMatchStage();
        }
    }

    getMatchStage = () => {
        const match = this.props.match;
        let className = '';
        let stage = '';
        switch (match.status) {
            case MatchStatus.NotStarted:
                const startTime = DateTimeService.convertFromUTC(match.startTime);
                className = 'font-weight-bold';
                stage = DateTimeService.toShortTimeFormat(startTime);
                break;

            case MatchStatus.FirstHalf:
                this.intervalId = setInterval(() => this.setState({ stage: this.getFirstHalfTimeMinutes(match) }), 1000 * 60);
                stage = this.getFirstHalfTimeMinutes(match);
                break;

            case MatchStatus.SecondHalf:
                this.intervalId = setInterval(() => this.setState({ stage: this.getSecondHalfTimeMinutes(match) }), 1000 * 60);
                stage = this.getSecondHalfTimeMinutes(match);
                break;

            case MatchStatus.ExtraTime:
                this.intervalId = setInterval(() => this.setState({ stage: this.getExtraTimeMinutes(match) }), 1000 * 60);
                stage = this.getExtraTimeMinutes(match);
                break;

            case MatchStatus.Intermission:
                stage = LangHelper.getResource('Intermission');
                break;

            case MatchStatus.PenaltyShootout:
                stage = LangHelper.getResource('Penalty');
                break;

            case MatchStatus.Delay:
                stage = LangHelper.getResource('Delay');
                break;

            case MatchStatus.Interrupt:
                stage = LangHelper.getResource('Interrupt');
                break;

            case MatchStatus.Postpone:
                stage = LangHelper.getResource('Postponed');
                break;

            case MatchStatus.Cancel:
                stage = LangHelper.getResource('Cancel');
                break;

            case MatchStatus.TBD:
                stage = LangHelper.getResource('ToBeDecided');
                break;

            default:
                stage = LangHelper.getResource('Finish');
                break;
        }

        this.setState({ stage, className });
    };

    getFirstHalfTimeMinutes = match => {
        const startTime = match.footballStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime));
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const minutes = currentTime.diff(startTime, 'minutes');
        return minutes <= 45 ? `${minutes}'` : `45'+`;
    };

    getSecondHalfTimeMinutes = match => {
        const startTime = match.footballNextHalfStartTime
            ? moment(DateTimeService.convertFromUTC(match.footballNextHalfStartTime))
            : moment(DateTimeService.convertFromUTC(match.startTime)).add(1, 'hours');
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        const minutes = currentTime.diff(startTime, 'minutes');
        return minutes <= 45 ? `${45 + minutes}'` : `90'+`;
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
        const { stage, className } = this.state;
        return (
            <div className="soccer-match-stage"><span className={className}>{stage}</span></div>
        );
    }
}