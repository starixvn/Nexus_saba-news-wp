const _rootUrl = document.getElementById('RootUrl') ? document.getElementById('RootUrl').value : '/';
let _xsrfToken = null;
import { MatchStatus, IncidentStatus, VARResultCode } from './constants';

export default class Site {
    static getXsrfToken() {
        if (!_xsrfToken && document.querySelector('input[name=__RequestVerificationToken]')) {
            _xsrfToken = document.querySelector('input[name=__RequestVerificationToken]').value;
        }

        return _xsrfToken;
    }

    static handleAxiosError(error) {
        if (error.response) {
            let url;
            switch (error.response.status) {
                case 401:
                    url = 'auth/logout';
                    break;

                case 404:
                    url = 'error/PageNotFound';
                    break;

                case 405:
                    url = 'error/AccessDenied';
                    break;

                case 500:
                    url = 'error/InternalError';
                    break;
            }

            //window.location = _rootUrl + url;
        }
    }

    static resolveClientUrl(relativeUrl) {
        return _rootUrl + relativeUrl;
    }

    static CheckPostMatchStatus(status) {
        if(status === MatchStatus.NotStarted || status === MatchStatus.Postpone || status === MatchStatus.Delay || status === MatchStatus.Cancel || status.TBD)  {
            return true;
        }
        return false;
    }

    static MappingStatusMatchText(status) {
        switch(status) {
            case MatchStatus.PenaltyShootout: return "Penalty";
            case MatchStatus.Finished: return "Kết thúc";
            case MatchStatus.Postpone: return "Hoãn";
            case MatchStatus.Interrupt: return "Gián đoạn";
            case MatchStatus.Postponement: return "Hoãn";
            case MatchStatus.Cancel: "Hủy";

        }
    }

    static CheckIncidentGeneralType(type) {
       if(type === IncidentStatus.Goal || type ===  IncidentStatus.YellowCard || type === IncidentStatus.RedCard || type === IncidentStatus.YellowTurnRedCard || type === IncidentStatus.OvertimeEnds
        || type === IncidentStatus.PenaltyMissed || type === IncidentStatus.OwnGoal || type === IncidentStatus.VAR || type === IncidentStatus.Penalty || type === IncidentStatus.Substitution) {
            return true;
        }
        return false;
    }

    static CheckIncidentMainTimeEventType(type) {
        if(type === IncidentStatus.End || type === IncidentStatus.OvertimeEnds
            || type === IncidentStatus.PenaltyShootoutEnd || type === IncidentStatus.Halftime) {
             return true;
         }
         return false;
    }


    static MappingIncidentTimeEventType(type) {
        switch(type) {
            case IncidentStatus.GameStart:
                return "Bắt đầu";
            case IncidentStatus.Halftime:
                return "Kết thúc hiệp 1";
            case IncidentStatus.End:
                return "Kết thúc hiệp 2";
            case IncidentStatus.ExtraTimeEnd:
                return "kết thúc hiệp phụ";
            case IncidentStatus.PenaltyShootoutEnd:
                return "Kết thúc penalty shootout"
        }
    }

    static MappingVarResultText(status) {
        switch(status) {
            case VARResultCode.Unknown:
                return "Không xác định";
            case VARResultCode.GoalValid:
                return "Bàn thắng hợp lệ";
            case VARResultCode.GoalInValid: 
                return "Bàn thắng không hợp lệ";
            case  VARResultCode.PenaltyValid:
                return "Phạt đền";
            case VARResultCode.PenaltyCancelled:
                return "Hủy phạt đền";
            case VARResultCode.RedCardValid:
                return "Thẻ đỏ";
            case VARResultCode.RedCardCanlled:
                return "Hủy thẻ đỏ";
            case VARResultCode.PlayPenaltyVerification:
                return "Play penalty verification";
            case VARResultCode.PlayPenaltyChange:
                return "Play penalty change";
            case VARResultCode.MaintainOriginalJudgment:
                return "Giữ nguyên quyết định";
            case VARResultCode.SendPenaltyChange:
                return "Send penalty change";

        }
    }
    static CheckIncidentTypeToShowScore(type) {
        if(type === IncidentStatus.Halftime ||  type == IncidentStatus.Penalty || type === IncidentStatus.Goal || type === IncidentStatus.OwnGoal) {
            return true;
        }
        return false;
    }

    static CheckIncidentTypeToUpdateScore(type) {
        if(type === IncidentStatus.Penalty || type === IncidentStatus.Goal || type === IncidentStatus.OwnGoal) {
            return true;
        }
        return false;
    }
}