import React, { PureComponent, Fragment } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    handleClick = (standingLength, number) =>{
        for(let num = 0; num < standingLength ; num ++){
            if(num != number){
                document.getElementsByClassName('standing-dropdown')[num].style.display = 'none';
            }
        }
        const display = document.getElementsByClassName('standing-dropdown')[number].style.display;
        if(display === 'block'){
            document.getElementsByClassName('standing-dropdown')[number].style.display = 'none';
        }
        else{
            document.getElementsByClassName('standing-dropdown')[number].style.display = 'block';
        }
    };

    render() {
        const { standing, number, standingLength } = this.props;
        return (
            <>
                <div className="minigame-table-items">
                    <div className="minigame-table-flex ">
                        <div className="table-row table-row-index">{number + 1}</div>
                        <div className="table-row table-row-name table-user">
                            <div className="user-avatar">
                                { standing.icon ? <img src={standing.icon} alt={standing.userName} />  : <span className="non-image">{standing.userName.slice(0,1)}</span> }
                            </div>
                            <div className="user-name">
                                {standing.userName}
                            </div>
                        </div>
                        <div className="table-row table-row-score">{standing.point}</div>
                        <div className="table-row table-row-time">
                            {standing.updatedDate != null ? standing.updatedDate : standing.createdDate}
                            {
                               window.stage === 4 ?  <i className="icon-bxh_view" id={"iconDetail" + (number + 1)} onClick={e => this.handleClick(standingLength, number)}/> : null
                            }
                            
                        </div>
                    </div>
                </div>
                {
                    window.stage === 4 && 
                    <div className="standing-dropdown" style={{display:'none'}}>
                        <div className="standing-dropdown-header">
                            <div className="standing-dropdown-row">
                                <div className="standing-dropdown-column round header-column">Vòng</div>
                                <div className="standing-dropdown-column score header-column">Điểm</div>
                            </div>
                        </div>
                        <div className="standing-dropdown-body">
                            <div className="standing-dropdown-row">
                                <div className="standing-dropdown-column round">Vòng 1/8</div>
                                <div className="standing-dropdown-column score">{standing.detail.pointEightTeamStage}</div>
                            </div>
                            <div className="standing-dropdown-row">
                                <div className="standing-dropdown-column round">Tứ Kết</div>
                                <div className="standing-dropdown-column score">{standing.detail.pointQuarterfinalsStage}</div>
                            </div>

                            <div className="standing-dropdown-row">
                                <div className="standing-dropdown-column round">Bán Kết</div>
                                <div className="standing-dropdown-column score">{standing.detail.pointSemifinalsStage}</div>
                            </div>
                            <div className="standing-dropdown-row">
                                <div className="standing-dropdown-column round">Tranh Hạng 3</div>
                                <div className="standing-dropdown-column score">{standing.detail.pointThirdPlace}</div>
                            </div>
                            <div className="standing-dropdown-row">
                                <div className="standing-dropdown-column round">Chung Kết</div>
                                <div className="standing-dropdown-column score">{standing.detail.pointFinalStage}</div>
                            </div>
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