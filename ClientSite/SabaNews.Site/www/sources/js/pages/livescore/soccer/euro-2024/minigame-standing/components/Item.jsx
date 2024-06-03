import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import DateTimeService from '@jsroot/common/dateTimeService';
import { ActionCreators } from '../actions';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    handleClick = (standingLength, number, stagesSelected) =>{
        if(stagesSelected === 4){
            for(let num = 0; num < standingLength ; num ++){
                if(num !== number){
                    document.getElementsByClassName('detailStandings')[num].style.display = 'none';
                }
            }
            const display = document.getElementsByClassName('detailStandings')[number].style.display;
            if(display === 'block'){
                document.getElementsByClassName('detailStandings')[number].style.display = 'none';
            }else{
                document.getElementsByClassName('detailStandings')[number].style.display = 'block';
            }
        }
    };

    checkUploadImg(standing){
        let result = false;
        const http = 'http://';
        const https = 'https://';
        if(standing.icon != null && !standing.icon.toLowerCase().includes(https) && !standing.icon.toLowerCase().includes(http)){
            result = true;
        }
        return result;
    }

    checkUploadLink(standing){
        const https = 'https://';
        const http = 'http://';
        let result = false;
        if(standing.icon != null && (standing.icon.toLowerCase().includes(https) || standing.icon.toLowerCase().includes(http))){
            result = true;
        }
        return result;
    }

    render() {
        const { standing, number, standingLength, rank, stagesSelected } = this.props;
        const updatedDate = standing.updatedDate != null ? standing.updatedDate : standing.createdDate;
        const dateShow = DateTimeService.formatDateTime(updatedDate, 'DD/MM/YYYY, HH:mm:ss');
        return (
            <>
                <div className={`minigame-table-items ${(number + 1) === rank ? 'active' : ''} ${stagesSelected === 4 ? 'cursor-items' : ''}`}
                onClick={() => this.handleClick(standingLength, number, stagesSelected)}>
                    <div className="minigame-table-flex ">
                        <div className="table-row table-row-index">{number + 1}</div>
                        <div className="table-row table-row-name table-user">
                            <div className="user-avatar">
                                {
                                    standing.icon === null && <span className="non-image">{standing.userName.slice(0,1)}</span>
                                }
                                {
                                    (this.checkUploadLink(standing)) &&
                                    <img src={standing.icon} alt={standing.userName.slice(0,1)} />
                                }
                                {
                                    this.checkUploadImg(standing) &&
                                    <img src={'/uploads/' + standing.icon} alt={standing.userName.slice(0,1)} />
                                }
                            </div>
                            <div className="user-name">
                                {standing.userName}
                            </div>
                        </div>
                        <div className="table-row table-row-score">{standing.point}</div>
                        <div className="table-row table-row-time">
                            {dateShow}
                        </div>
                    </div>
                </div>
                {/* <div className="table-dropdown detailStandings">
                    <div className="table-dropdown-header">
                        <div className="table-dropdown-round">Vòng</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">Điểm</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Vòng 1/8</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">11</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Tứ Kết</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">11</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Bán Kết</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">11</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Tranh Hạng 3</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">11</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Chung Kết</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">11</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                </div> */}
                {
                    stagesSelected === 4 && <div className="table-dropdown detailStandings">
                    <div className="table-dropdown-header">
                        <div className="table-dropdown-round">Vòng</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">Điểm</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Vòng 1/8</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">{standing.detail.pointEightTeamStage}</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Tứ Kết</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">{standing.detail.pointQuarterfinalsStage}</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Bán Kết</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">{standing.detail.pointSemifinalsStage}</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Tranh Hạng 3</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">{standing.detail.pointThirdPlace}</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                    <div className="table-dropdown-body">
                        <div className="table-dropdown-round">Chung Kết</div>
                        <div className="table-dropdown-name"></div>
                        <div className="table-dropdown-score">{standing.detail.pointFinalStage}</div>
                        <div className="table-dropdown-time"></div>
                    </div>
                </div>
                }
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, stages, league, votes } = state;
    return { matches, stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(Item);