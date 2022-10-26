import React, { PureComponent, Fragment } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    handleClick = (number) =>{
        const display = document.getElementsByClassName('detail')[number].style.display;
        if(display === 'block'){
            document.getElementsByClassName('detail')[number].style.display = 'none';
        }
        else{
            document.getElementsByClassName('detail')[number].style.display = 'block';
        }
    };

    render() {
        const { standing, number } = this.props;
        return (
            <>
                <div className="minigame-table-items">
                    <div className="minigame-table-flex ">
                        <div className="table-row table-row-index">{number + 1}</div>
                        <div className="table-row table-row-name table-user">
                            <div className="user-avatar">
                                { standing.icon ? <img src={standing.icon} alt={standing.userName} />  : <span className="non-image">A</span> }
                            </div>
                            <div className="user-name">
                                {standing.userName}
                            </div>
                        </div>
                        <div className="table-row table-row-score">{standing.point}</div>
                        <div className="table-row table-row-time">
                            {standing.updatedDate != null ? standing.updatedDate : standing.createdDate}
                            {
                               window.stage === 4 ?  <i className="icon-bxh_view" id={"iconDetail" + (number + 1)} onClick={e => this.handleClick(number)}/> : null
                            }
                            
                        </div>
                    </div>
                </div>
                {
                    window.stage === 4 && 
                    <div className="detail" style={{display:'none', justifyContent:'space-between'}}>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Vòng</div>
                            <div>Diểm</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Vòng 1/8</div>
                            <div>{standing.detail.pointEightTeamStage}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Tứ Kết</div>
                            <div>{standing.detail.pointQuarterfinalsStage}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Bán Kết</div>
                            <div>{standing.detail.pointSemifinalsStage}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Tranh Hạng 3</div>
                            <div>{standing.detail.pointThirdPlace}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Chung Kết</div>
                            <div>{standing.detail.pointFinalStage}</div>
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