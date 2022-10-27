import React, { PureComponent, Fragment } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    handleClick = (winnnerLength, number) =>{
        for(let num = 0; num < winnnerLength ; num ++){
            if(num != number){
                document.getElementsByClassName('Winner-dropdown')[num].style.display = 'none';
            }
        }
        const display = document.getElementsByClassName('Winner-dropdown')[number].style.display;
        if(display === 'block'){
            document.getElementsByClassName('Winner-dropdown')[number].style.display = 'none';
        }
        else{
            document.getElementsByClassName('Winner-dropdown')[number].style.display = 'block';
        }
    };

    render() {
        const { Winner, number, winnnerLength } = this.props;
        return (
            <>
                {
                    number < 10 && 
                        <div className="minigame-table-items">
                            <div className="minigame-table-flex ">
                                <div className="table-row table-row-index">{number + 1}</div>
                                <div className="table-row table-row-name table-user">
                                    <div className="user-avatar">
                                        { Winner.icon ? <img src={Winner.icon} alt={Winner.userName} />  : <span className="non-image">{Winner.userName.slice(0,1)}</span> }
                                    </div>
                                    <div className="user-name">
                                        {Winner.userName}
                                    </div>
                                </div>
                                <div className="table-row table-row-score">{Winner.point}</div>
                                <div className="table-row table-row-time">
                                    {Winner.updatedDate != null ? Winner.updatedDate : Winner.createdDate}
                                    {
                                    window.stage === 4 ?  <i className="icon-bxh_view" id={"iconDetail" + (number + 1)} onClick={e => this.handleClick(winnnerLength, number)}/> : null
                                    }
                                    
                                </div>
                            </div>
                        </div>
                }
                {
                    number < 10 && window.stage === 4 && 
                    <div className="Winner-dropdown" style={{display:'none'}}>
                        <div className="Winner-dropdown-row">
                            <div className="Winner-dropdown-column round header-column">Vòng</div>
                            <div className="Winner-dropdown-column score header-column">Điểm</div>
                        </div>
                        <div className="Winner-dropdown-row">
                            <div className="Winner-dropdown-column round">Vòng 1/8</div>
                            <div className="Winner-dropdown-column score">{Winner.detail.pointEightTeamStage}</div>
                        </div>
                        <div className="Winner-dropdown-row">
                            <div className="Winner-dropdown-column round">Tứ Kết</div>
                            <div className="Winner-dropdown-column score">{Winner.detail.pointQuarterfinalsStage}</div>
                        </div>

                        <div className="Winner-dropdown-row">
                            <div className="Winner-dropdown-column round">Bán Kết</div>
                            <div className="Winner-dropdown-column score">{Winner.detail.pointSemifinalsStage}</div>
                        </div>
                        <div className="Winner-dropdown-row">
                            <div className="Winner-dropdown-column round">Tranh Hạng 3</div>
                            <div className="Winner-dropdown-column score">{Winner.detail.pointThirdPlace}</div>
                        </div>
                        <div className="Winner-dropdown-row">
                            <div className="Winner-dropdown-column round">Chung Kết</div>
                            <div className="Winner-dropdown-column score">{Winner.detail.pointFinalStage}</div>
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